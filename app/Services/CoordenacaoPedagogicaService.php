<?php

namespace App\Services;

use App\Models\AcompanhamentoPedagogicoAluno;
use App\Models\DiarioProfessor;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\PendenciaProfessor;
use App\Models\PlanejamentoAnual;
use App\Models\PlanejamentoPeriodo;
use App\Models\PlanejamentoSemanal;
use App\Models\RegistroAula;
use App\Models\Turma;
use App\Models\Usuario;
use App\Models\ValidacaoPedagogica;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CoordenacaoPedagogicaService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function listarDiarios(Usuario $usuario, array $filtros = [], int $paginacao = 12): LengthAwarePaginator
    {
        $query = DiarioProfessor::query()
            ->with([
                'escola',
                'turma.modalidade',
                'disciplina',
                'professor',
                'planejamentoAnual.validacaoPedagogica',
                'planejamentosPeriodo.validacaoPedagogica',
                'planejamentosSemanais.validacaoPedagogica',
                'registrosAula.validacaoPedagogica',
                'acompanhamentosPedagogicos',
            ])
            ->withCount([
                'registrosAula',
                'planejamentosPeriodo',
                'planejamentosSemanais',
                'pendencias',
                'acompanhamentosPedagogicos as alunos_em_risco_count' => function (Builder $query) {
                    $query->whereIn('situacao_risco', ['moderado', 'alto', 'critico']);
                },
            ]);

        $this->aplicarEscopoEscola($query, $usuario);

        foreach (['escola_id', 'turma_id', 'disciplina_id', 'professor_id', 'ano_letivo'] as $campo) {
            if (! empty($filtros[$campo])) {
                $query->where($campo, $filtros[$campo]);
            }
        }

        if (! empty($filtros['periodo_tipo'])) {
            $query->where('periodo_tipo', $filtros['periodo_tipo']);
        }

        if (! empty($filtros['periodo_referencia'])) {
            $query->where('periodo_referencia', $filtros['periodo_referencia']);
        }

        if (! empty($filtros['situacao_validacao'])) {
            $status = $filtros['situacao_validacao'];

            $query->where(function (Builder $subquery) use ($status) {
                $subquery->whereHas('planejamentoAnual.validacaoPedagogica', fn (Builder $q) => $q->where('status', $status))
                    ->orWhereHas('planejamentosPeriodo.validacaoPedagogica', fn (Builder $q) => $q->where('status', $status))
                    ->orWhereHas('planejamentosSemanais.validacaoPedagogica', fn (Builder $q) => $q->where('status', $status))
                    ->orWhereHas('registrosAula.validacaoPedagogica', fn (Builder $q) => $q->where('status', $status));
            });
        }

        return $query
            ->orderByDesc('ano_letivo')
            ->orderBy('turma_id')
            ->orderBy('disciplina_id')
            ->paginate($paginacao)
            ->withQueryString();
    }

    public function obterPainelDiario(Usuario $usuario, DiarioProfessor $diario): array
    {
        $this->garantirAcessoAoDiario($usuario, $diario);

        $diario = $diario->load([
            'escola',
            'turma.modalidade',
            'disciplina',
            'professor',
            'planejamentoAnual.validacaoPedagogica.usuarioCoordenador',
            'planejamentosPeriodo.validacaoPedagogica.usuarioCoordenador',
            'planejamentosSemanais.validacaoPedagogica.usuarioCoordenador',
            'registrosAula.validacaoPedagogica.usuarioCoordenador',
            'registrosAula.frequencias.matricula.aluno',
            'observacoesAluno.matricula.aluno',
            'ocorrencias.matricula.aluno',
            'pendencias.usuarioRegistro',
            'acompanhamentosPedagogicos.matricula.aluno',
            'acompanhamentosPedagogicos.usuarioCoordenador',
            'lancamentosAvaliativos.matricula.aluno',
        ]);

        $matriculas = Matricula::query()
            ->with('aluno')
            ->where('turma_id', $diario->turma_id)
            ->where('status', 'ativa')
            ->orderBy('id')
            ->get();

        $frequenciaPorMatricula = $this->calcularResumoFrequencia($diario, $matriculas);
        $alunosEmRisco = $this->resolverAlunosEmRisco($diario, $frequenciaPorMatricula);

        return [
            'diario' => $diario,
            'matriculas' => $matriculas,
            'frequenciaPorMatricula' => $frequenciaPorMatricula,
            'alunosEmRisco' => $alunosEmRisco,
            'metricas' => [
                'planejamento_anual_validado' => $diario->planejamentoAnual?->validacaoPedagogica?->status === 'validado',
                'planejamentos_periodo_validados' => $diario->planejamentosPeriodo
                    ->filter(fn ($planejamento) => $planejamento->validacaoPedagogica?->status === 'validado')
                    ->count(),
                'planejamentos_semanais_validados' => $diario->planejamentosSemanais
                    ->filter(fn (PlanejamentoSemanal $planejamento) => $planejamento->validacaoPedagogica?->status === 'validado')
                    ->count(),
                'aulas_validadas' => $diario->registrosAula
                    ->filter(fn (RegistroAula $registro) => $registro->validacaoPedagogica?->status === 'validado')
                    ->count(),
                'avaliacoes_lancadas' => $diario->lancamentosAvaliativos->count(),
                'pendencias_abertas' => $diario->pendencias
                    ->whereIn('status', ['aberta', 'em_andamento'])
                    ->count(),
                'alunos_em_risco' => $alunosEmRisco->count(),
            ],
        ];
    }

    public function validarPlanejamentoAnual(
        Usuario $usuario,
        DiarioProfessor $diario,
        PlanejamentoAnual $planejamento,
        array $dados
    ): ValidacaoPedagogica {
        $this->garantirAcessoAoDiario($usuario, $diario);
        $this->garantirItemPertenceAoDiario($diario, $planejamento->diario_professor_id);

        return $this->salvarValidacao($diario, $planejamento, $usuario, $dados);
    }

    public function validarPlanejamentoPeriodo(
        Usuario $usuario,
        DiarioProfessor $diario,
        PlanejamentoPeriodo $planejamento,
        array $dados
    ): ValidacaoPedagogica {
        $this->garantirAcessoAoDiario($usuario, $diario);
        $this->garantirItemPertenceAoDiario($diario, $planejamento->diario_professor_id);

        return $this->salvarValidacao($diario, $planejamento, $usuario, $dados);
    }

    public function validarPlanejamentoSemanal(
        Usuario $usuario,
        DiarioProfessor $diario,
        PlanejamentoSemanal $planejamento,
        array $dados
    ): ValidacaoPedagogica {
        $this->garantirAcessoAoDiario($usuario, $diario);
        $this->garantirItemPertenceAoDiario($diario, $planejamento->diario_professor_id);

        return $this->salvarValidacao($diario, $planejamento, $usuario, $dados);
    }

    public function validarRegistroAula(
        Usuario $usuario,
        DiarioProfessor $diario,
        RegistroAula $registroAula,
        array $dados
    ): ValidacaoPedagogica {
        $this->garantirAcessoAoDiario($usuario, $diario);
        $this->garantirItemPertenceAoDiario($diario, $registroAula->diario_professor_id);

        return $this->salvarValidacao($diario, $registroAula, $usuario, $dados);
    }

    public function salvarAcompanhamentoAluno(
        Usuario $usuario,
        DiarioProfessor $diario,
        Matricula $matricula,
        array $dados
    ): AcompanhamentoPedagogicoAluno {
        $this->garantirAcessoAoDiario($usuario, $diario);

        if ((int) $matricula->turma_id !== (int) $diario->turma_id) {
            throw ValidationException::withMessages([
                'matricula_id' => 'A matricula informada nao pertence a turma deste diario.',
            ]);
        }

        $percentualFrequencia = $this->calcularResumoFrequencia($diario, collect([$matricula]))
            ->get($matricula->id)['percentual_presenca'] ?? null;

        return DB::transaction(function () use ($diario, $matricula, $usuario, $dados, $percentualFrequencia) {
            return AcompanhamentoPedagogicoAluno::updateOrCreate(
                [
                    'diario_professor_id' => $diario->id,
                    'matricula_id' => $matricula->id,
                ],
                [
                    'usuario_coordenador_id' => $usuario->id,
                    'nivel_rendimento' => $dados['nivel_rendimento'],
                    'situacao_risco' => $dados['situacao_risco'],
                    'percentual_frequencia' => $percentualFrequencia,
                    'indicativos_aprendizagem' => $dados['indicativos_aprendizagem'],
                    'fatores_risco' => $dados['fatores_risco'] ?? null,
                    'encaminhamentos' => $dados['encaminhamentos'] ?? null,
                    'precisa_intervencao' => (bool) ($dados['precisa_intervencao'] ?? false),
                ]
            );
        });
    }

    public function criarPendenciaDocente(
        Usuario $usuario,
        DiarioProfessor $diario,
        array $dados
    ): PendenciaProfessor {
        $this->garantirAcessoAoDiario($usuario, $diario);

        return DB::transaction(function () use ($usuario, $diario, $dados) {
            return PendenciaProfessor::create([
                'diario_professor_id' => $diario->id,
                'professor_id' => $diario->professor_id,
                'usuario_registro_id' => $usuario->id,
                'titulo' => $dados['titulo'],
                'descricao' => $dados['descricao'],
                'origem' => 'coordenacao',
                'prazo' => $dados['prazo'] ?? null,
                'status' => $dados['status'],
            ]);
        });
    }

    public function opcoesFiltros(Usuario $usuario): array
    {
        $diarios = DiarioProfessor::query()
            ->with(['escola', 'turma', 'disciplina', 'professor'])
            ->tap(fn (Builder $query) => $this->aplicarEscopoEscola($query, $usuario))
            ->get();

        return [
            'escolas' => $diarios->pluck('escola')->filter()->unique('id')->sortBy('nome')->values(),
            'turmas' => $diarios->pluck('turma')->filter()->unique('id')->sortBy('nome')->values(),
            'disciplinas' => $diarios->pluck('disciplina')->filter()->unique('id')->sortBy('nome')->values(),
            'professores' => $diarios->pluck('professor')->filter()->unique('id')->sortBy('nome')->values(),
        ];
    }

    public function atualizarLancamentoAvaliativo(
        Usuario $usuario,
        DiarioProfessor $diario,
        LancamentoAvaliativo $lancamento,
        array $dados
    ): LancamentoAvaliativo {
        $this->garantirAcessoAoDiario($usuario, $diario);

        if ((int) $lancamento->diario_professor_id !== (int) $diario->id) {
            throw ValidationException::withMessages([
                'avaliacao' => 'O lancamento informado nao pertence ao diario selecionado.',
            ]);
        }

        $tipoAvaliacao = app(DiarioProfessorService::class)->resolverTipoAvaliacao($diario->loadMissing('turma.modalidade'));

        if ($tipoAvaliacao === 'nota' && empty($dados['valor_numerico']) && $dados['valor_numerico'] !== 0 && $dados['valor_numerico'] !== '0') {
            throw ValidationException::withMessages([
                'valor_numerico' => 'A modalidade desta turma exige nota numerica.',
            ]);
        }

        if (in_array($tipoAvaliacao, ['conceito', 'parecer'], true) && empty($dados['conceito'])) {
            throw ValidationException::withMessages([
                'conceito' => 'A modalidade desta turma exige conceito.',
            ]);
        }

        return DB::transaction(function () use ($lancamento, $dados, $tipoAvaliacao) {
            $lancamento->update([
                'tipo_avaliacao' => $tipoAvaliacao,
                'avaliacao_referencia' => $dados['avaliacao_referencia'],
                'valor_numerico' => $tipoAvaliacao === 'nota' ? ($dados['valor_numerico'] ?? null) : null,
                'conceito' => in_array($tipoAvaliacao, ['conceito', 'parecer'], true) ? ($dados['conceito'] ?? null) : null,
                'observacoes' => $dados['observacoes'] ?? null,
            ]);

            return $lancamento->refresh();
        });
    }

    public function ajustarRegistroAula(
        Usuario $usuario,
        DiarioProfessor $diario,
        RegistroAula $registroAula,
        array $dados
    ): RegistroAula {
        $this->garantirAcessoAoDiario($usuario, $diario);
        $this->garantirItemPertenceAoDiario($diario, $registroAula->diario_professor_id);

        return DB::transaction(function () use ($registroAula, $dados) {
            $registroAula->update([
                'data_aula' => $dados['data_aula'],
                'titulo' => $dados['titulo'],
                'conteudo_previsto' => $dados['conteudo_previsto'] ?? null,
                'conteudo_ministrado' => $dados['conteudo_ministrado'],
                'metodologia' => $dados['metodologia'] ?? null,
                'recursos_utilizados' => $dados['recursos_utilizados'] ?? null,
                'quantidade_aulas' => $dados['quantidade_aulas'],
                'aula_dada' => (bool) ($dados['aula_dada'] ?? true),
            ]);

            return $registroAula->refresh();
        });
    }

    public function listarHorarios(Usuario $usuario, array $filtros = [], int $paginacao = 20): LengthAwarePaginator
    {
        $query = HorarioAula::query()->with(['escola', 'turma.modalidade', 'disciplina', 'professor']);

        $this->aplicarEscopoEscola($query, $usuario);

        foreach (['escola_id', 'turma_id', 'professor_id'] as $campo) {
            if (! empty($filtros[$campo])) {
                $query->where($campo, $filtros[$campo]);
            }
        }

        if (! empty($filtros['turno'])) {
            $query->whereHas('turma', fn (Builder $subquery) => $subquery->where('turno', $filtros['turno']));
        }

        return $query
            ->orderBy('turma_id')
            ->orderBy('dia_semana')
            ->orderBy('horario_inicial')
            ->paginate($paginacao)
            ->withQueryString();
    }

    public function opcoesHorarios(Usuario $usuario): array
    {
        $escolaIds = $this->resolverEscolaIds($usuario);

        return [
            'escolas' => Escola::query()
                ->when(! $usuario->hasRole('Administrador da Rede'), fn (Builder $query) => $query->whereIn('id', $escolaIds))
                ->orderBy('nome')
                ->get(),
            'turmas' => Turma::query()
                ->with('modalidade')
                ->when(! $usuario->hasRole('Administrador da Rede'), fn (Builder $query) => $query->whereIn('escola_id', $escolaIds))
                ->orderBy('nome')
                ->get(),
            'disciplinas' => Disciplina::query()->orderBy('nome')->get(),
            'professores' => Funcionario::query()
                ->whereHas('escolas', function (Builder $query) use ($usuario, $escolaIds) {
                    if (! $usuario->hasRole('Administrador da Rede')) {
                        $query->whereIn('escolas.id', $escolaIds);
                    }
                })
                ->orderBy('nome')
                ->get(),
        ];
    }

    public function criarHorarios(Usuario $usuario, array $dados): int
    {
        $this->garantirEscolaNoEscopo($usuario, (int) $dados['escola_id']);

        return DB::transaction(function () use ($dados) {
            $inseridos = 0;

            foreach ($dados['horarios'] as $horario) {
                HorarioAula::create([
                    'escola_id' => $dados['escola_id'],
                    'turma_id' => $dados['turma_id'],
                    'disciplina_id' => $horario['disciplina_id'],
                    'professor_id' => $horario['professor_id'] ?? null,
                    'dia_semana' => $horario['dia_semana'],
                    'horario_inicial' => $horario['horario_inicial'],
                    'horario_final' => $horario['horario_final'],
                    'ordem_aula' => $horario['ordem_aula'] ?? null,
                    'ativo' => true,
                ]);

                $inseridos++;
            }

            return $inseridos;
        });
    }

    public function atualizarHorario(Usuario $usuario, HorarioAula $horario, array $dados): HorarioAula
    {
        $this->garantirEscolaNoEscopo($usuario, (int) $horario->escola_id);
        $this->garantirEscolaNoEscopo($usuario, (int) $dados['escola_id']);

        return DB::transaction(function () use ($horario, $dados) {
            $horario->update([
                'escola_id' => $dados['escola_id'],
                'turma_id' => $dados['turma_id'],
                'dia_semana' => $dados['dia_semana'],
                'horario_inicial' => $dados['horario_inicial'],
                'horario_final' => $dados['horario_final'],
                'disciplina_id' => $dados['disciplina_id'],
                'professor_id' => $dados['professor_id'] ?? null,
                'ordem_aula' => $dados['ordem_aula'] ?? null,
                'ativo' => (bool) ($dados['ativo'] ?? $horario->ativo),
            ]);

            return $horario->refresh();
        });
    }

    public function garantirAcessoHorario(Usuario $usuario, HorarioAula $horario): void
    {
        $this->garantirEscolaNoEscopo($usuario, (int) $horario->escola_id);
    }

    private function salvarValidacao(DiarioProfessor $diario, mixed $validavel, Usuario $usuario, array $dados): ValidacaoPedagogica
    {
        return DB::transaction(function () use ($diario, $validavel, $usuario, $dados) {
            $validacao = $validavel->validacaoPedagogica()->updateOrCreate(
                [],
                [
                    'diario_professor_id' => $diario->id,
                    'usuario_coordenador_id' => $usuario->id,
                    'status' => $dados['status'],
                    'parecer' => $dados['parecer'],
                    'observacoes_internas' => $dados['observacoes_internas'] ?? null,
                    'validado_em' => now(),
                ]
            );

            $this->auditoriaService->registrarEvento([
                'usuario_id' => $usuario->id,
                'escola_id' => $diario->escola_id,
                'modulo' => $validavel instanceof RegistroAula ? 'aulas' : 'planejamentos',
                'acao' => 'validacao_pedagogica',
                'tipo_registro' => 'Validacao Pedagogica',
                'registro_type' => $validacao::class,
                'registro_id' => $validacao->id,
                'nivel_sensibilidade' => 'medio',
                'contexto' => [
                    'diario_professor_id' => $diario->id,
                    'professor_id' => $diario->professor_id,
                    'turma_id' => $diario->turma_id,
                    'status' => $dados['status'],
                ],
                'valores_depois' => [
                    'status' => $dados['status'],
                    'parecer' => $dados['parecer'],
                ],
            ]);

            return $validacao;
        });
    }

    private function calcularResumoFrequencia(DiarioProfessor $diario, Collection $matriculas): Collection
    {
        $registros = $diario->registrosAula;

        return $matriculas->mapWithKeys(function (Matricula $matricula) use ($registros) {
            $frequencias = $registros
                ->flatMap(fn (RegistroAula $registro) => $registro->frequencias)
                ->where('matricula_id', $matricula->id)
                ->values();

            $totalLancado = $frequencias->count();
            $presentes = $frequencias->whereIn('situacao', ['presente', 'atraso'])->count();
            $faltas = $frequencias->whereIn('situacao', ['falta', 'falta_justificada'])->count();
            $percentualPresenca = $totalLancado > 0
                ? round(($presentes / $totalLancado) * 100, 2)
                : null;

            return [
                $matricula->id => [
                    'matricula' => $matricula,
                    'total_lancado' => $totalLancado,
                    'presentes' => $presentes,
                    'faltas' => $faltas,
                    'percentual_presenca' => $percentualPresenca,
                ],
            ];
        });
    }

    private function resolverAlunosEmRisco(DiarioProfessor $diario, Collection $frequenciaPorMatricula): Collection
    {
        $acompanhamentos = $diario->acompanhamentosPedagogicos->keyBy('matricula_id');

        return $frequenciaPorMatricula
            ->filter(function (array $resumo, int $matriculaId) use ($acompanhamentos) {
                $acompanhamento = $acompanhamentos->get($matriculaId);
                $frequenciaBaixa = $resumo['percentual_presenca'] !== null && $resumo['percentual_presenca'] < 85;
                $riscoSinalizado = $acompanhamento
                    && in_array($acompanhamento->situacao_risco, ['moderado', 'alto', 'critico'], true);

                return $frequenciaBaixa || $riscoSinalizado;
            })
            ->map(function (array $resumo, int $matriculaId) use ($acompanhamentos) {
                return [
                    ...$resumo,
                    'acompanhamento' => $acompanhamentos->get($matriculaId),
                ];
            })
            ->values();
    }

    private function aplicarEscopoEscola(Builder $query, Usuario $usuario): void
    {
        $escolaIds = $this->resolverEscolaIds($usuario);

        if (! $usuario->hasRole('Administrador da Rede')) {
            if (count($escolaIds) === 0) {
                $query->whereRaw('1 = 0');

                return;
            }

            $query->whereIn('escola_id', $escolaIds);
        }
    }

    private function garantirAcessoAoDiario(Usuario $usuario, DiarioProfessor $diario): void
    {
        if ($usuario->hasRole('Administrador da Rede')) {
            return;
        }

        $escolaIds = $this->resolverEscolaIds($usuario);

        if (! in_array((int) $diario->escola_id, array_map('intval', $escolaIds), true)) {
            throw ValidationException::withMessages([
                'diario' => 'O diario informado nao pertence ao escopo da coordenacao autenticada.',
            ]);
        }
    }

    private function garantirItemPertenceAoDiario(DiarioProfessor $diario, int $diarioProfessorId): void
    {
        if ((int) $diario->id !== (int) $diarioProfessorId) {
            throw ValidationException::withMessages([
                'diario' => 'O item informado nao pertence ao diario selecionado.',
            ]);
        }
    }

    private function garantirEscolaNoEscopo(Usuario $usuario, int $escolaId): void
    {
        if ($usuario->hasRole('Administrador da Rede')) {
            return;
        }

        if (! in_array($escolaId, array_map('intval', $this->resolverEscolaIds($usuario)), true)) {
            throw ValidationException::withMessages([
                'escola_id' => 'A escola informada nao pertence ao escopo da coordenacao autenticada.',
            ]);
        }
    }

    private function resolverEscolaIds(Usuario $usuario): array
    {
        return $usuario->escolas()->pluck('escolas.id')->all();
    }
}
