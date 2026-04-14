<?php

namespace App\Services;

use App\Models\DiarioProfessor;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\FaltaFuncionario;
use App\Models\FechamentoLetivo;
use App\Models\FrequenciaAula;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\JustificativaFaltaAluno;
use App\Models\LancamentoAvaliativo;
use App\Models\LiberacaoPrazoProfessor;
use App\Models\PendenciaProfessor;
use App\Models\PlanejamentoAnual;
use App\Models\PlanejamentoPeriodo;
use App\Models\PlanejamentoSemanal;
use App\Models\RegistroAula;
use App\Models\Turma;
use App\Models\Usuario;
use App\Models\ValidacaoDirecao;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DirecaoEscolarService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function listarDiarios(Usuario $usuario, array $filtros = [], int $paginacao = 10): LengthAwarePaginator
    {
        $query = DiarioProfessor::query()
            ->with([
                'escola',
                'turma.modalidade',
                'disciplina',
                'professor',
                'planejamentoAnual.validacaoDirecao',
                'planejamentosPeriodo.validacaoDirecao',
                'planejamentosSemanais.validacaoDirecao',
                'registrosAula.validacaoDirecao',
            ])
            ->withCount([
                'registrosAula',
                'planejamentosPeriodo',
                'pendencias',
                'justificativasFaltaAluno as faltas_justificadas_count',
                'liberacoesPrazo as liberacoes_ativas_count' => function (Builder $query) {
                    $query->where('status', 'ativa')
                        ->whereDate('data_limite', '>=', now()->toDateString());
                },
            ]);

        $this->aplicarEscopoEscola($query, $usuario);

        foreach (['escola_id', 'turma_id', 'disciplina_id', 'professor_id', 'ano_letivo'] as $campo) {
            if (! empty($filtros[$campo])) {
                $query->where($campo, $filtros[$campo]);
            }
        }

        if (! empty($filtros['situacao_validacao'])) {
            $status = $filtros['situacao_validacao'];

            $query->where(function (Builder $subquery) use ($status) {
                $subquery->whereHas('planejamentoAnual.validacaoDirecao', fn (Builder $q) => $q->where('status', $status))
                    ->orWhereHas('planejamentosPeriodo.validacaoDirecao', fn (Builder $q) => $q->where('status', $status))
                    ->orWhereHas('planejamentosSemanais.validacaoDirecao', fn (Builder $q) => $q->where('status', $status))
                    ->orWhereHas('registrosAula.validacaoDirecao', fn (Builder $q) => $q->where('status', $status));
            });
        }

        return $query
            ->orderByDesc('ano_letivo')
            ->orderBy('turma_id')
            ->orderBy('disciplina_id')
            ->paginate($paginacao)
            ->withQueryString();
    }

    public function obterPainelInicial(Usuario $usuario, array $filtros = []): array
    {
        $escolaIds = $this->resolverEscolaIds($usuario);

        $faltasFuncionarios = FaltaFuncionario::query()
            ->with(['escola', 'funcionario'])
            ->when(! $usuario->hasRole('Administrador da Rede'), fn (Builder $query) => $query->whereIn('escola_id', $escolaIds))
            ->orderByDesc('data_falta')
            ->limit(8)
            ->get();

        $fechamentosLetivos = FechamentoLetivo::query()
            ->with('escola')
            ->when(! $usuario->hasRole('Administrador da Rede'), fn (Builder $query) => $query->whereIn('escola_id', $escolaIds))
            ->orderByDesc('ano_letivo')
            ->limit(8)
            ->get();

        $horarios = HorarioAula::query()
            ->with(['escola', 'turma', 'disciplina', 'professor'])
            ->when(! $usuario->hasRole('Administrador da Rede'), fn (Builder $query) => $query->whereIn('escola_id', $escolaIds))
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when(! empty($filtros['turma_id']), fn (Builder $query) => $query->where('turma_id', $filtros['turma_id']))
            ->orderBy('dia_semana')
            ->orderBy('horario_inicial')
            ->orderBy('ordem_aula')
            ->limit(10)
            ->get();

        return [
            'faltasFuncionarios' => $faltasFuncionarios,
            'fechamentosLetivos' => $fechamentosLetivos,
            'horarios' => $horarios,
            'filtros' => $this->opcoesFiltros($usuario),
        ];
    }

    public function obterPainelDiario(Usuario $usuario, DiarioProfessor $diario): array
    {
        $this->garantirAcessoAoDiario($usuario, $diario);

        $diario = $diario->load([
            'escola',
            'turma.modalidade',
            'disciplina',
            'professor',
            'planejamentoAnual.validacaoDirecao.usuarioDirecao',
            'planejamentosPeriodo.validacaoDirecao.usuarioDirecao',
            'planejamentosSemanais.validacaoDirecao.usuarioDirecao',
            'registrosAula.validacaoDirecao.usuarioDirecao',
            'registrosAula.frequencias.matricula.aluno',
            'registrosAula.frequencias.justificativaDirecao.usuarioDirecao',
            'pendencias.usuarioRegistro',
            'liberacoesPrazo.usuarioDirecao',
            'lancamentosAvaliativos.matricula.aluno',
        ]);

        $frequenciasPassiveisJustificativa = $diario->registrosAula
            ->flatMap(fn (RegistroAula $registro) => $registro->frequencias)
            ->filter(fn (FrequenciaAula $frequencia) => in_array($frequencia->situacao, ['falta', 'falta_justificada'], true))
            ->sortByDesc(fn (FrequenciaAula $frequencia) => optional($frequencia->registroAula->data_aula)->timestamp ?? 0)
            ->values();

        $horariosTurma = HorarioAula::query()
            ->with(['disciplina', 'professor'])
            ->where('turma_id', $diario->turma_id)
            ->orderBy('dia_semana')
            ->orderBy('horario_inicial')
            ->orderBy('ordem_aula')
            ->get();

        return [
            'diario' => $diario,
            'frequenciasPassiveisJustificativa' => $frequenciasPassiveisJustificativa,
            'horariosTurma' => $horariosTurma,
            'metricas' => [
                'planejamentos_validados' => ($diario->planejamentoAnual?->validacaoDirecao?->status === 'validado' ? 1 : 0)
                    + $diario->planejamentosPeriodo->filter(fn ($planejamento) => $planejamento->validacaoDirecao?->status === 'validado')->count()
                    + $diario->planejamentosSemanais->filter(fn (PlanejamentoSemanal $planejamento) => $planejamento->validacaoDirecao?->status === 'validado')->count(),
                'aulas_validadas' => $diario->registrosAula->filter(fn (RegistroAula $registro) => $registro->validacaoDirecao?->status === 'validado')->count(),
                'avaliacoes_lancadas' => $diario->lancamentosAvaliativos->count(),
                'faltas_justificadas' => $diario->justificativasFaltaAluno()->count(),
                'liberacoes_ativas' => $diario->liberacoesPrazo()->where('status', 'ativa')->whereDate('data_limite', '>=', now()->toDateString())->count(),
                'pendencias_abertas' => $diario->pendencias->whereIn('status', ['aberta', 'em_andamento'])->count(),
            ],
        ];
    }

    public function validarPlanejamento(
        Usuario $usuario,
        DiarioProfessor $diario,
        PlanejamentoAnual|PlanejamentoSemanal|PlanejamentoPeriodo $planejamento,
        array $dados
    ): ValidacaoDirecao {
        $this->garantirAcessoAoDiario($usuario, $diario);
        $this->garantirItemPertenceAoDiario($diario, $planejamento->diario_professor_id);

        return $this->salvarValidacao($diario, $planejamento, $usuario, $dados);
    }

    public function validarRegistroAula(
        Usuario $usuario,
        DiarioProfessor $diario,
        RegistroAula $registro,
        array $dados
    ): ValidacaoDirecao {
        $this->garantirAcessoAoDiario($usuario, $diario);
        $this->garantirItemPertenceAoDiario($diario, $registro->diario_professor_id);

        return $this->salvarValidacao($diario, $registro, $usuario, $dados);
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
        RegistroAula $registro,
        array $dados
    ): RegistroAula {
        $this->garantirAcessoAoDiario($usuario, $diario);
        $this->garantirItemPertenceAoDiario($diario, $registro->diario_professor_id);

        return DB::transaction(function () use ($registro, $dados) {
            $registro->update([
                'data_aula' => $dados['data_aula'],
                'titulo' => $dados['titulo'],
                'conteudo_previsto' => $dados['conteudo_previsto'] ?? null,
                'conteudo_ministrado' => $dados['conteudo_ministrado'],
                'metodologia' => $dados['metodologia'] ?? null,
                'recursos_utilizados' => $dados['recursos_utilizados'] ?? null,
                'quantidade_aulas' => $dados['quantidade_aulas'],
                'aula_dada' => (bool) ($dados['aula_dada'] ?? true),
            ]);

            return $registro->refresh();
        });
    }

    public function justificarFaltaAluno(
        Usuario $usuario,
        DiarioProfessor $diario,
        FrequenciaAula $frequencia,
        array $dados
    ): JustificativaFaltaAluno {
        $this->garantirAcessoAoDiario($usuario, $diario);

        $frequencia->loadMissing('registroAula', 'matricula');

        if ((int) $frequencia->registroAula->diario_professor_id !== (int) $diario->id) {
            throw ValidationException::withMessages([
                'frequencia' => 'A frequencia informada nao pertence ao diario selecionado.',
            ]);
        }

        if (! in_array($frequencia->situacao, ['falta', 'falta_justificada'], true)) {
            throw ValidationException::withMessages([
                'frequencia' => 'Somente faltas podem ser justificadas pela direcao.',
            ]);
        }

        return DB::transaction(function () use ($usuario, $diario, $frequencia, $dados) {
            $justificativa = JustificativaFaltaAluno::updateOrCreate(
                ['frequencia_aula_id' => $frequencia->id],
                [
                    'diario_professor_id' => $diario->id,
                    'matricula_id' => $frequencia->matricula_id,
                    'usuario_direcao_id' => $usuario->id,
                    'situacao_anterior' => $frequencia->getOriginal('situacao'),
                    'situacao_atual' => 'falta_justificada',
                    'motivo' => $dados['motivo'],
                    'documento_comprobatorio' => $dados['documento_comprobatorio'] ?? null,
                    'deferida_em' => now(),
                ]
            );

            $frequencia->update([
                'situacao' => 'falta_justificada',
                'justificativa' => $dados['motivo'],
                'observacao' => $dados['documento_comprobatorio'] ?? $frequencia->observacao,
            ]);

            return $justificativa;
        });
    }

    public function liberarPrazoLancamento(Usuario $usuario, DiarioProfessor $diario, array $dados): LiberacaoPrazoProfessor
    {
        $this->garantirAcessoAoDiario($usuario, $diario);

        return DB::transaction(function () use ($usuario, $diario, $dados) {
            return LiberacaoPrazoProfessor::create([
                'diario_professor_id' => $diario->id,
                'professor_id' => $diario->professor_id,
                'usuario_direcao_id' => $usuario->id,
                'tipo_lancamento' => $dados['tipo_lancamento'],
                'data_limite' => $dados['data_limite'],
                'status' => 'ativa',
                'motivo' => $dados['motivo'],
                'observacoes' => $dados['observacoes'] ?? null,
            ]);
        });
    }

    public function registrarFaltaFuncionario(Usuario $usuario, array $dados): FaltaFuncionario
    {
        $escolaId = (int) $dados['escola_id'];
        $this->garantirEscolaNoEscopo($usuario, $escolaId);

        $funcionarioPertence = Funcionario::query()
            ->whereKey($dados['funcionario_id'])
            ->whereHas('escolas', fn (Builder $query) => $query->where('escolas.id', $escolaId))
            ->exists();

        if (! $funcionarioPertence) {
            throw ValidationException::withMessages([
                'funcionario_id' => 'O funcionario informado nao pertence a escola selecionada.',
            ]);
        }

        return FaltaFuncionario::create([
            'escola_id' => $escolaId,
            'funcionario_id' => $dados['funcionario_id'],
            'usuario_registro_id' => $usuario->id,
            'data_falta' => $dados['data_falta'],
            'turno' => $dados['turno'],
            'tipo_falta' => $dados['tipo_falta'],
            'justificada' => (bool) ($dados['justificada'] ?? false),
            'motivo' => $dados['motivo'],
            'observacoes' => $dados['observacoes'] ?? null,
        ]);
    }

    public function salvarFechamentoLetivo(Usuario $usuario, array $dados): FechamentoLetivo
    {
        $escolaId = (int) $dados['escola_id'];
        $anoLetivo = (int) $dados['ano_letivo'];

        $this->garantirEscolaNoEscopo($usuario, $escolaId);

        $diariosNoAno = DiarioProfessor::query()
            ->where('escola_id', $escolaId)
            ->where('ano_letivo', $anoLetivo)
            ->count();

        if ($diariosNoAno === 0) {
            throw ValidationException::withMessages([
                'ano_letivo' => 'Nao ha diarios registrados para iniciar o fechamento deste ano letivo.',
            ]);
        }

        if ($dados['status'] === 'concluido') {
            $pendenciasAbertas = PendenciaProfessor::query()
                ->whereHas('diarioProfessor', function (Builder $query) use ($escolaId, $anoLetivo) {
                    $query->where('escola_id', $escolaId)->where('ano_letivo', $anoLetivo);
                })
                ->whereIn('status', ['aberta', 'em_andamento'])
                ->count();

            if ($pendenciasAbertas > 0) {
                throw ValidationException::withMessages([
                    'status' => 'Existem pendencias docentes abertas para este ano letivo. Conclua-as antes do fechamento final.',
                ]);
            }
        }

        return DB::transaction(function () use ($usuario, $escolaId, $anoLetivo, $dados) {
            $registro = FechamentoLetivo::firstOrNew([
                'escola_id' => $escolaId,
                'ano_letivo' => $anoLetivo,
            ]);

            $registro->usuario_direcao_id = $usuario->id;
            $registro->status = $dados['status'];
            $registro->resumo = $dados['resumo'] ?? null;
            $registro->observacoes = $dados['observacoes'] ?? null;
            $registro->iniciado_em = $registro->iniciado_em ?? now();
            $registro->concluido_em = $dados['status'] === 'concluido' ? now() : null;
            $registro->save();

            return $registro;
        });
    }

    public function opcoesFiltros(Usuario $usuario): array
    {
        $diarios = DiarioProfessor::query()
            ->with(['escola', 'turma', 'disciplina', 'professor'])
            ->tap(fn (Builder $query) => $this->aplicarEscopoEscola($query, $usuario))
            ->get();

        $funcionarios = Funcionario::query()
            ->with('escolas')
            ->when(! $usuario->hasRole('Administrador da Rede'), function (Builder $query) use ($usuario) {
                $escolaIds = $this->resolverEscolaIds($usuario);
                $query->whereHas('escolas', fn (Builder $subquery) => $subquery->whereIn('escolas.id', $escolaIds));
            })
            ->orderBy('nome')
            ->get();

        return [
            'escolas' => $diarios->pluck('escola')->filter()->unique('id')->sortBy('nome')->values(),
            'turmas' => $diarios->pluck('turma')->filter()->unique('id')->sortBy('nome')->values(),
            'disciplinas' => $diarios->pluck('disciplina')->filter()->unique('id')->sortBy('nome')->values(),
            'professores' => $diarios->pluck('professor')->filter()->unique('id')->sortBy('nome')->values(),
            'funcionarios' => $funcionarios,
        ];
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

    private function salvarValidacao(DiarioProfessor $diario, mixed $validavel, Usuario $usuario, array $dados): ValidacaoDirecao
    {
        return DB::transaction(function () use ($diario, $validavel, $usuario, $dados) {
            $validacao = $validavel->validacaoDirecao()->updateOrCreate(
                [],
                [
                    'diario_professor_id' => $diario->id,
                    'usuario_direcao_id' => $usuario->id,
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
                'acao' => 'validacao_direcao',
                'tipo_registro' => 'Validacao da Direcao',
                'registro_type' => $validacao::class,
                'registro_id' => $validacao->id,
                'nivel_sensibilidade' => 'alto',
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

    private function aplicarEscopoEscola(Builder $query, Usuario $usuario): void
    {
        if ($usuario->hasRole('Administrador da Rede')) {
            return;
        }

        $escolaIds = $this->resolverEscolaIds($usuario);

        if (count($escolaIds) === 0) {
            $query->whereRaw('1 = 0');

            return;
        }

        $query->whereIn('escola_id', $escolaIds);
    }

    private function garantirAcessoAoDiario(Usuario $usuario, DiarioProfessor $diario): void
    {
        if ($usuario->hasRole('Administrador da Rede')) {
            return;
        }

        $escolaIds = array_map('intval', $this->resolverEscolaIds($usuario));

        if (! in_array((int) $diario->escola_id, $escolaIds, true)) {
            throw ValidationException::withMessages([
                'diario' => 'O diario informado nao pertence ao escopo da direcao autenticada.',
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

        $escolaIds = array_map('intval', $this->resolverEscolaIds($usuario));

        if (! in_array($escolaId, $escolaIds, true)) {
            throw ValidationException::withMessages([
                'escola_id' => 'A escola informada nao pertence ao escopo da direcao autenticada.',
            ]);
        }
    }

    private function resolverEscolaIds(Usuario $usuario): array
    {
        return $usuario->escolas()->pluck('escolas.id')->all();
    }
}
