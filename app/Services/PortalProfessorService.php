<?php

namespace App\Services;

use App\Models\DiarioProfessor;
use App\Models\HorarioAula;
use App\Models\Usuario;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PortalProfessorService
{
    public function __construct(
        private readonly DiarioProfessorService $diarioProfessorService
    ) {
    }

    public function obterContextoProfessor(Usuario $usuario): array
    {
        $funcionario = $this->diarioProfessorService->resolverFuncionario($usuario);

        if (! $funcionario) {
            abort(403, 'Usuario sem vinculo de professor.');
        }

        return [$usuario, $funcionario];
    }

    public function obterDadosDashboard(Usuario $usuario): array
    {
        [, $funcionario] = $this->obterContextoProfessor($usuario);

        $diarios = DiarioProfessor::query()
            ->with(['turma', 'disciplina', 'escola', 'pendencias'])
            ->where('professor_id', $funcionario->id)
            ->orderByDesc('ano_letivo')
            ->orderBy('periodo_tipo')
            ->get();

        $horarios = HorarioAula::query()
            ->with(['turma', 'disciplina', 'escola'])
            ->where('professor_id', $funcionario->id)
            ->where('ativo', true)
            ->orderBy('dia_semana')
            ->orderBy('horario_inicial')
            ->get();

        $turmas = $this->listarMinhasTurmas($usuario);
        $pendenciasAbertas = $diarios->flatMap(fn (DiarioProfessor $diario) => $diario->pendencias)
            ->whereIn('status', ['aberta', 'em_andamento']);

        $hojeIndice = (int) now()->dayOfWeek + 1;
        $aulasHoje = $horarios->where('dia_semana', $hojeIndice)->values();

        return [
            'funcionario' => $funcionario,
            'totais' => [
                'turmas' => $turmas->count(),
                'diarios' => $diarios->count(),
                'aulas_hoje' => $aulasHoje->count(),
                'pendencias_abertas' => $pendenciasAbertas->count(),
            ],
            'diariosRecentes' => $diarios->take(4),
            'aulasHoje' => $aulasHoje,
            'turmas' => $turmas->take(4),
            'proximaAcao' => $this->resolverProximaAcao($diarios, $turmas),
        ];
    }

    public function listarMinhasTurmas(Usuario $usuario): Collection
    {
        [, $funcionario] = $this->obterContextoProfessor($usuario);

        $horarios = HorarioAula::query()
            ->with(['turma.escola', 'turma.modalidade', 'disciplina'])
            ->where('professor_id', $funcionario->id)
            ->where('ativo', true)
            ->get();

        $diarios = DiarioProfessor::query()
            ->where('professor_id', $funcionario->id)
            ->get()
            ->groupBy(fn (DiarioProfessor $diario) => $diario->turma_id . '-' . $diario->disciplina_id);

        return $horarios
            ->groupBy(fn (HorarioAula $horario) => $horario->turma_id . '-' . $horario->disciplina_id)
            ->map(function (Collection $grupo, string $chave) use ($diarios) {
                /** @var HorarioAula $primeiro */
                $primeiro = $grupo->first();

                return (object) [
                    'chave' => $chave,
                    'turma' => $primeiro->turma,
                    'disciplina' => $primeiro->disciplina,
                    'escola' => $primeiro->escola,
                    'turno' => $primeiro->turma->turno,
                    'quantidade_aulas_semanais' => $grupo->count(),
                    'diarios' => $diarios->get($chave, collect()),
                    'horarios' => $grupo->sortBy(fn (HorarioAula $horario) => sprintf('%02d-%s', $horario->dia_semana, $horario->horario_inicial))->values(),
                ];
            })
            ->sortBy(fn (object $item) => $item->turma->nome . $item->disciplina->nome)
            ->values();
    }

    public function listarMeuHorario(Usuario $usuario): Collection
    {
        [, $funcionario] = $this->obterContextoProfessor($usuario);

        $diasSemana = [
            1 => 'Domingo',
            2 => 'Segunda-feira',
            3 => 'Terca-feira',
            4 => 'Quarta-feira',
            5 => 'Quinta-feira',
            6 => 'Sexta-feira',
            7 => 'Sabado',
        ];

        return HorarioAula::query()
            ->with(['turma', 'disciplina', 'escola'])
            ->where('professor_id', $funcionario->id)
            ->where('ativo', true)
            ->orderBy('dia_semana')
            ->orderBy('horario_inicial')
            ->get()
            ->groupBy('dia_semana')
            ->map(function (Collection $itens, int $dia) use ($diasSemana) {
                return [
                    'rotulo' => $diasSemana[$dia] ?? 'Dia ' . $dia,
                    'itens' => $itens->values(),
                ];
            });
    }

    public function construirBreadcrumbs(array $itens): array
    {
        $breadcrumbs = [
            [
                'label' => 'Portal do Professor',
                'url' => route('professor.dashboard'),
            ],
        ];

        foreach ($itens as $item) {
            $breadcrumbs[] = $item;
        }

        return $breadcrumbs;
    }

    private function resolverProximaAcao(Collection $diarios, Collection $turmas): ?array
    {
        $primeiroDiario = $diarios->first();

        if ($primeiroDiario) {
            return [
                'titulo' => 'Continuar diario eletronico',
                'descricao' => 'Retome o diario de ' . $primeiroDiario->turma->nome . ' em ' . Str::lower($primeiroDiario->disciplina->nome) . '.',
                'url' => route('professor.diario.show', $primeiroDiario),
            ];
        }

        $primeiraTurma = $turmas->first();

        if ($primeiraTurma) {
            return [
                'titulo' => 'Criar primeiro diario',
                'descricao' => 'Abra o diario da turma ' . $primeiraTurma->turma->nome . ' para comecar seus registros.',
                'url' => route('professor.diario.create'),
            ];
        }

        return null;
    }
}
