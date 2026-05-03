<?php

namespace App\Services;

use App\Models\DiarioProfessor;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\ObservacaoAluno;
use App\Models\OcorrenciaDiario;
use App\Models\PendenciaProfessor;
use App\Models\PlanejamentoAnual;
use App\Models\PlanejamentoPeriodo;
use App\Models\RegistroAula;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DiarioProfessorService
{
    public function listarDiariosParaUsuario(Usuario $usuario, array $filtros = [], int $paginacao = 12): LengthAwarePaginator
    {
        $query = DiarioProfessor::query()
            ->with(['escola', 'turma', 'disciplina', 'professor'])
            ->withCount(['registrosAula', 'observacoesAluno', 'ocorrencias', 'pendencias']);

        $this->aplicarEscopoDeAcesso($query, $usuario);
        $this->aplicarFiltros($query, $filtros);

        return $query
            ->orderByDesc('ano_letivo')
            ->orderBy('periodo_tipo')
            ->orderBy('periodo_referencia')
            ->paginate($paginacao)
            ->withQueryString();
    }

    private function aplicarFiltros(Builder $query, array $filtros): void
    {
        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (! empty($filtros['turma_id'])) {
            $query->where('turma_id', $filtros['turma_id']);
        }

        if (! empty($filtros['disciplina_id'])) {
            $query->where('disciplina_id', $filtros['disciplina_id']);
        }

        if (! empty($filtros['professor_id'])) {
            $query->where('professor_id', $filtros['professor_id']);
        }

        if (! empty($filtros['ano_letivo'])) {
            $query->where('ano_letivo', $filtros['ano_letivo']);
        }

        if (! empty($filtros['periodo_tipo'])) {
            $query->where('periodo_tipo', $filtros['periodo_tipo']);
        }

        if (! empty($filtros['periodo_referencia'])) {
            $query->where('periodo_referencia', $filtros['periodo_referencia']);
        }
    }

    public function opcoesCriacaoParaUsuario(Usuario $usuario): Collection
    {
        $funcionario = $this->resolverFuncionario($usuario);

        if (! $funcionario) {
            return collect();
        }

        return HorarioAula::query()
            ->with(['escola', 'turma.modalidade', 'disciplina'])
            ->where('professor_id', $funcionario->id)
            ->where('ativo', true)
            ->get()
            ->unique(fn (HorarioAula $horario) => implode('-', [
                $horario->escola_id,
                $horario->turma_id,
                $horario->disciplina_id,
            ]))
            ->values();
    }

    public function criarDiario(Usuario $usuario, array $dados): DiarioProfessor
    {
        $funcionario = $this->resolverFuncionario($usuario);

        if (! $funcionario) {
            throw ValidationException::withMessages([
                'professor' => 'Nao foi encontrado vinculo de professor para o usuario autenticado.',
            ]);
        }

        $horarioVinculado = HorarioAula::query()
            ->where('professor_id', $funcionario->id)
            ->where('escola_id', $dados['escola_id'])
            ->where('turma_id', $dados['turma_id'])
            ->where('disciplina_id', $dados['disciplina_id'])
            ->where('ativo', true)
            ->exists();

        if (! $horarioVinculado) {
            throw ValidationException::withMessages([
                'turma_id' => 'A turma e disciplina selecionadas nao pertencem aos horarios do professor.',
            ]);
        }

        $jaExiste = DiarioProfessor::query()
            ->where('turma_id', $dados['turma_id'])
            ->where('disciplina_id', $dados['disciplina_id'])
            ->where('professor_id', $funcionario->id)
            ->where('ano_letivo', $dados['ano_letivo'])
            ->where('periodo_tipo', $dados['periodo_tipo'])
            ->where('periodo_referencia', $dados['periodo_referencia'])
            ->exists();

        if ($jaExiste) {
            throw ValidationException::withMessages([
                'periodo_referencia' => 'Ja existe um diario para essa turma, disciplina e periodo.',
            ]);
        }

        return DB::transaction(function () use ($dados, $funcionario) {
            return DiarioProfessor::create([
                'escola_id' => $dados['escola_id'],
                'turma_id' => $dados['turma_id'],
                'disciplina_id' => $dados['disciplina_id'],
                'professor_id' => $funcionario->id,
                'ano_letivo' => $dados['ano_letivo'],
                'periodo_tipo' => $dados['periodo_tipo'],
                'periodo_referencia' => $dados['periodo_referencia'],
                'observacoes_gerais' => $dados['observacoes_gerais'] ?? null,
            ]);
        });
    }

    public function obterDiarioDetalhado(DiarioProfessor $diario): DiarioProfessor
    {
        return $diario->load([
            'escola',
            'turma.modalidade',
            'disciplina',
            'professor',
            'planejamentoAnual.validacaoPedagogica',
            'planejamentosAnuais',
            'planejamentosPeriodo',
            'planejamentosPeriodo.validacaoPedagogica',
            'planejamentosPeriodo.validacaoDirecao',
            'registrosAula.horarioAula',
            'registrosAula.frequencias.matricula.aluno',
            'observacoesAluno.matricula.aluno',
            'ocorrencias.matricula.aluno',
            'pendencias.professor',
            'lancamentosAvaliativos.matricula.aluno',
        ]);
    }

    public function listarMatriculasAtivas(DiarioProfessor $diario): Collection
    {
        return Matricula::query()
            ->with('aluno')
            ->where('turma_id', $diario->turma_id)
            ->where('status', 'ativa')
            ->orderBy('tipo')
            ->orderBy('id')
            ->get();
    }

    public function listarHorariosDoDiario(DiarioProfessor $diario): Collection
    {
        return HorarioAula::query()
            ->where('turma_id', $diario->turma_id)
            ->where('disciplina_id', $diario->disciplina_id)
            ->where('professor_id', $diario->professor_id)
            ->where('ativo', true)
            ->orderBy('dia_semana')
            ->orderBy('horario_inicial')
            ->get();
    }

    public function salvarPlanejamentoAnual(DiarioProfessor $diario, array $dados): void
    {
        $aguardando = PlanejamentoAnual::where('diario_professor_id', $diario->id)
            ->where('status', PlanejamentoAnual::STATUS_ENVIADO)
            ->exists();

        if ($aguardando) {
            throw ValidationException::withMessages([
                'planejamento_anual' => 'O planejamento anual esta aguardando aprovacao da coordenacao e nao pode ser alterado.',
            ]);
        }

        DB::transaction(function () use ($diario, $dados) {
            foreach ([1, 2, 3, 4] as $i) {
                $u = $dados['unidades'][$i] ?? [];

                PlanejamentoAnual::updateOrCreate(
                    ['diario_professor_id' => $diario->id, 'unidade' => $i],
                    [
                        'diario_professor_id' => $diario->id,
                        'unidade'              => $i,
                        'status'               => PlanejamentoAnual::STATUS_RASCUNHO,
                        'tema_gerador'         => $u['tema_gerador'] ?? null,
                        'objetivos_gerais'     => $u['objetivos_aprendizagem'] ?? null,
                        'competencias_habilidades' => $u['habilidades_competencias'] ?? null,
                        'conteudos'            => $u['conteudos'] ?? null,
                        'metodologia'          => $u['metodologia'] ?? null,
                        'estrategias_pedagogicas' => $u['estrategias_pedagogicas'] ?? null,
                        'recursos_didaticos'   => $u['recursos_didaticos'] ?? null,
                        'instrumentos_avaliacao' => $u['instrumentos_avaliacao'] ?? null,
                        'adequacoes_inclusao'  => $u['adequacoes_inclusao'] ?? null,
                        'observacoes'          => $u['observacoes'] ?? null,
                        'referencias'          => $u['referencias'] ?? null,
                    ]
                );
            }
        });
    }

    public function enviarPlanejamentoAnual(DiarioProfessor $diario): void
    {
        $planejamentos = PlanejamentoAnual::where('diario_professor_id', $diario->id)->get();

        if ($planejamentos->isEmpty()) {
            throw ValidationException::withMessages([
                'planejamento_anual' => 'Nenhuma unidade do planejamento anual foi registrada.',
            ]);
        }

        $referencia = $planejamentos->first();

        if (! in_array($referencia->status, [PlanejamentoAnual::STATUS_RASCUNHO, PlanejamentoAnual::STATUS_DEVOLVIDO], true)) {
            throw ValidationException::withMessages([
                'planejamento_anual' => 'O planejamento anual nao pode ser enviado com status: ' . $referencia->status . '.',
            ]);
        }

        DB::transaction(function () use ($planejamentos) {
            foreach ($planejamentos as $p) {
                $p->update(['status' => PlanejamentoAnual::STATUS_ENVIADO]);
            }
        });
    }

    public function enviarPlanejamentoPeriodo(DiarioProfessor $diario, PlanejamentoPeriodo $planejamento): PlanejamentoPeriodo
    {
        if ((int) $planejamento->diario_professor_id !== (int) $diario->id) {
            throw ValidationException::withMessages([
                'planejamento' => 'Este planejamento nao pertence ao diario informado.',
            ]);
        }

        if (! in_array($planejamento->status, [PlanejamentoPeriodo::STATUS_RASCUNHO, PlanejamentoPeriodo::STATUS_DEVOLVIDO], true)) {
            throw ValidationException::withMessages([
                'planejamento' => 'Este planejamento nao pode ser enviado com status: ' . $planejamento->status . '.',
            ]);
        }

        return DB::transaction(function () use ($planejamento) {
            $planejamento->update(['status' => PlanejamentoPeriodo::STATUS_ENVIADO]);
            return $planejamento->refresh();
        });
    }

    public function salvarPlanejamentoPeriodo(DiarioProfessor $diario, array $dados): PlanejamentoPeriodo
    {
        return DB::transaction(function () use ($diario, $dados) {
            $planejamentoId = $dados['planejamento_periodo_id'] ?? null;
            unset($dados['planejamento_periodo_id']);

            if ($planejamentoId) {
                $planejamento = PlanejamentoPeriodo::query()
                    ->where('diario_professor_id', $diario->id)
                    ->whereKey($planejamentoId)
                    ->firstOrFail();

                if (! in_array($planejamento->status, [PlanejamentoPeriodo::STATUS_RASCUNHO, PlanejamentoPeriodo::STATUS_DEVOLVIDO], true)) {
                    throw ValidationException::withMessages([
                        'planejamento_periodo_id' => 'Este planejamento nao pode ser alterado porque ja foi enviado para aprovacao ou aprovado.',
                    ]);
                }

                $duplicado = PlanejamentoPeriodo::query()
                    ->where('diario_professor_id', $diario->id)
                    ->where('tipo_planejamento', $dados['tipo_planejamento'])
                    ->whereDate('data_inicio', $dados['data_inicio'])
                    ->whereKeyNot($planejamento->id)
                    ->exists();

                if ($duplicado) {
                    throw ValidationException::withMessages([
                        'data_inicio' => 'Ja existe outro planejamento para este tipo e data inicial neste diario.',
                    ]);
                }

                $planejamento->update($dados + [
                    'status' => PlanejamentoPeriodo::STATUS_RASCUNHO,
                ]);

                return $planejamento->refresh();
            }

            $planejamento = PlanejamentoPeriodo::query()
                ->where('diario_professor_id', $diario->id)
                ->where('tipo_planejamento', $dados['tipo_planejamento'])
                ->whereDate('data_inicio', $dados['data_inicio'])
                ->first();

            if ($planejamento && ! in_array($planejamento->status, [PlanejamentoPeriodo::STATUS_RASCUNHO, PlanejamentoPeriodo::STATUS_DEVOLVIDO], true)) {
                throw ValidationException::withMessages([
                    'data_inicio' => 'Ja existe um planejamento para este tipo e data inicial aguardando aprovacao ou ja aprovado.',
                ]);
            }

            if ($planejamento) {
                $planejamento->update($dados + [
                    'status' => PlanejamentoPeriodo::STATUS_RASCUNHO,
                ]);

                return $planejamento->refresh();
            }

            return PlanejamentoPeriodo::create($dados + [
                'diario_professor_id' => $diario->id,
                'status' => PlanejamentoPeriodo::STATUS_RASCUNHO,
            ]);
        });
    }

    public function registrarAula(DiarioProfessor $diario, array $dados, int $usuarioId): RegistroAula
    {
        return DB::transaction(function () use ($diario, $dados, $usuarioId) {
            return RegistroAula::create($dados + [
                'diario_professor_id' => $diario->id,
                'usuario_registro_id' => $usuarioId,
            ]);
        });
    }

    public function lancarFrequencia(DiarioProfessor $diario, array $dados): void
    {
        $registroAula = $diario->registrosAula()
            ->whereKey($dados['registro_aula_id'])
            ->firstOrFail();

        $matriculasPermitidas = $this->listarMatriculasAtivas($diario)->pluck('id')->all();

        DB::transaction(function () use ($registroAula, $dados, $matriculasPermitidas) {
            foreach ($dados['frequencias'] as $frequencia) {
                if (! in_array($frequencia['matricula_id'], $matriculasPermitidas, true)) {
                    throw ValidationException::withMessages([
                        'frequencias' => 'Existe aluno informado fora da turma vinculada ao diario.',
                    ]);
                }

                $registroAula->frequencias()->updateOrCreate(
                    ['matricula_id' => $frequencia['matricula_id']],
                    [
                        'situacao' => $frequencia['situacao'],
                        'justificativa' => $frequencia['justificativa'] ?? null,
                        'observacao' => $frequencia['observacao'] ?? null,
                    ]
                );
            }
        });
    }

    public function registrarObservacao(DiarioProfessor $diario, array $dados, int $usuarioId): ObservacaoAluno
    {
        $this->validarMatriculaPertenceAoDiario($diario, $dados['matricula_id']);

        return DB::transaction(function () use ($diario, $dados, $usuarioId) {
            return $diario->observacoesAluno()->create($dados + [
                'usuario_registro_id' => $usuarioId,
            ]);
        });
    }

    public function registrarOcorrencia(DiarioProfessor $diario, array $dados, int $usuarioId): OcorrenciaDiario
    {
        if (! empty($dados['matricula_id'])) {
            $this->validarMatriculaPertenceAoDiario($diario, $dados['matricula_id']);
        }

        return DB::transaction(function () use ($diario, $dados, $usuarioId) {
            return $diario->ocorrencias()->create($dados + [
                'usuario_registro_id' => $usuarioId,
            ]);
        });
    }

    public function registrarPendencia(DiarioProfessor $diario, array $dados, int $usuarioId): PendenciaProfessor
    {
        return DB::transaction(function () use ($diario, $dados, $usuarioId) {
            return $diario->pendencias()->create($dados + [
                'professor_id' => $diario->professor_id,
                'usuario_registro_id' => $usuarioId,
            ]);
        });
    }

    public function lancarAvaliacao(DiarioProfessor $diario, array $dados, int $usuarioId): LancamentoAvaliativo
    {
        $this->validarMatriculaPertenceAoDiario($diario, $dados['matricula_id']);

        $tipoAvaliacao = $this->resolverTipoAvaliacao($diario);

        if ($tipoAvaliacao === 'nota' && empty($dados['valor_numerico']) && $dados['valor_numerico'] !== 0 && $dados['valor_numerico'] !== '0') {
            throw ValidationException::withMessages([
                'valor_numerico' => 'A modalidade desta turma exige o lancamento de nota numerica.',
            ]);
        }

        if (in_array($tipoAvaliacao, ['conceito', 'parecer'], true) && empty($dados['conceito'])) {
            throw ValidationException::withMessages([
                'conceito' => 'A modalidade desta turma exige o lancamento de conceito.',
            ]);
        }

        if ($tipoAvaliacao === 'nota') {
            $dados['conceito'] = null;
        }

        if (in_array($tipoAvaliacao, ['conceito', 'parecer'], true)) {
            $dados['valor_numerico'] = null;
        }

        return DB::transaction(function () use ($diario, $dados, $usuarioId, $tipoAvaliacao) {
            return LancamentoAvaliativo::updateOrCreate(
                [
                    'diario_professor_id' => $diario->id,
                    'matricula_id' => $dados['matricula_id'],
                    'avaliacao_referencia' => $dados['avaliacao_referencia'],
                ],
                [
                    'usuario_registro_id' => $usuarioId,
                    'tipo_avaliacao' => $tipoAvaliacao,
                    'valor_numerico' => $dados['valor_numerico'] ?? null,
                    'conceito' => $dados['conceito'] ?? null,
                    'observacoes' => $dados['observacoes'] ?? null,
                ]
            );
        });
    }

    public function resolverFuncionario(?Usuario $usuario): ?Funcionario
    {
        return $usuario?->resolverFuncionario();
    }

    private function aplicarEscopoDeAcesso(Builder $query, Usuario $usuario): void
    {
        $funcionario = $this->resolverFuncionario($usuario);

        if ($usuario->can('consultar diarios') && ! $usuario->hasRole('Professor')) {
            $escolaIds = $usuario->escolas()->pluck('escolas.id')->all();

            if (count($escolaIds) === 0 && ! $usuario->hasRole('Administrador da Rede')) {
                $query->whereRaw('1 = 0');

                return;
            }

            if (! $usuario->hasRole('Administrador da Rede')) {
                $query->whereIn('escola_id', $escolaIds);
            }

            return;
        }

        if ($funcionario) {
            $query->where('professor_id', $funcionario->id);

            return;
        }

        $query->whereRaw('1 = 0');
    }

    private function validarMatriculaPertenceAoDiario(DiarioProfessor $diario, int $matriculaId): void
    {
        $matriculaValida = Matricula::query()
            ->whereKey($matriculaId)
            ->where('turma_id', $diario->turma_id)
            ->exists();

        if (! $matriculaValida) {
            throw ValidationException::withMessages([
                'matricula_id' => 'O aluno informado nao pertence a turma deste diario.',
            ]);
        }
    }

    public function resolverTipoAvaliacao(DiarioProfessor $diario): string
    {
        $tipoAvaliacao = strtolower((string) data_get($diario, 'turma.modalidade.tipo_avaliacao', ''));

        if (str_contains($tipoAvaliacao, 'nota')) {
            return 'nota';
        }

        if (str_contains($tipoAvaliacao, 'conceito')) {
            return 'conceito';
        }

        if (str_contains($tipoAvaliacao, 'parecer')) {
            return 'parecer';
        }

        return 'nota';
    }
}
