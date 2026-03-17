<?php

namespace App\Services;

use App\Models\DiarioProfessor;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\PlanejamentoAnual;
use App\Models\PlanejamentoPeriodo;
use App\Models\PlanejamentoSemanal;
use App\Models\RegistroAula;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DiarioProfessorService
{
    public function listarDiariosParaUsuario(Usuario $usuario, array $filtros = [], int $paginacao = 12)
    {
        $query = DiarioProfessor::query()
            ->with(['escola', 'turma', 'disciplina', 'professor'])
            ->withCount(['registrosAula', 'observacoesAluno', 'ocorrencias', 'pendencias']);

        $this->aplicarEscopoDeAcesso($query, $usuario);

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

        return $query
            ->orderByDesc('ano_letivo')
            ->orderBy('periodo_tipo')
            ->orderBy('periodo_referencia')
            ->paginate($paginacao)
            ->withQueryString();
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
            'planejamentoAnual',
            'planejamentosSemanais',
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

    public function salvarPlanejamentoAnual(DiarioProfessor $diario, array $dados): PlanejamentoAnual
    {
        return DB::transaction(function () use ($diario, $dados) {
            return PlanejamentoAnual::updateOrCreate(
                ['diario_professor_id' => $diario->id],
                $dados + ['diario_professor_id' => $diario->id]
            );
        });
    }

    public function salvarPlanejamentoPeriodo(DiarioProfessor $diario, array $dados): PlanejamentoPeriodo
    {
        return DB::transaction(function () use ($diario, $dados) {
            return PlanejamentoPeriodo::create($dados + [
                'diario_professor_id' => $diario->id,
            ]);
        });
    }

    public function salvarPlanejamentoSemanal(DiarioProfessor $diario, array $dados): PlanejamentoSemanal
    {
        return DB::transaction(function () use ($diario, $dados) {
            return PlanejamentoSemanal::create($dados + [
                'diario_professor_id' => $diario->id,
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

    public function registrarObservacao(DiarioProfessor $diario, array $dados, int $usuarioId)
    {
        $this->validarMatriculaPertenceAoDiario($diario, $dados['matricula_id']);

        return DB::transaction(function () use ($diario, $dados, $usuarioId) {
            return $diario->observacoesAluno()->create($dados + [
                'usuario_registro_id' => $usuarioId,
            ]);
        });
    }

    public function registrarOcorrencia(DiarioProfessor $diario, array $dados, int $usuarioId)
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

    public function registrarPendencia(DiarioProfessor $diario, array $dados, int $usuarioId)
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
