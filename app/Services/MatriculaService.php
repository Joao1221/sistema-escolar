<?php

namespace App\Services;

use App\Models\Matricula;
use App\Models\MatriculaHistorico;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MatriculaService
{
    /**
     * Realiza uma nova matrícula (Regular ou AEE).
     */
    public function realizarMatricula(array $data): Matricula
    {
        return DB::transaction(function () use ($data) {
            $matricula = Matricula::create([
                'aluno_id' => $data['aluno_id'],
                'escola_id' => $data['escola_id'],
                'turma_id' => $data['turma_id'] ?? null,
                'ano_letivo' => $data['ano_letivo'],
                'tipo' => $data['tipo'], // 'regular' ou 'aee'
                'status' => 'ativa',
                'matricula_regular_id' => $data['matricula_regular_id'] ?? null,
                'data_matricula' => $data['data_matricula'] ?? now(),
                'observacoes' => $data['observacoes'] ?? null,
            ]);

            $this->registrarHistorico(
                $matricula,
                'criacao',
                "Matrícula {$matricula->tipo} realizada para o ano letivo {$matricula->ano_letivo}."
            );

            return $matricula;
        });
    }

    /**
     * Enturmar um aluno.
     */
    public function enturmar(Matricula $matricula, int $turmaId): Matricula
    {
        return DB::transaction(function () use ($matricula, $turmaId) {
            $matricula->update(['turma_id' => $turmaId]);

            $this->registrarHistorico(
                $matricula,
                'enturmacao',
                "Aluno alocado na turma ID: {$turmaId}."
            );

            return $matricula;
        });
    }

    /**
     * Realizar transferência de aluno.
     */
    public function transferir(Matricula $matricula, string $motivo): Matricula
    {
        return DB::transaction(function () use ($matricula, $motivo) {
            $matricula->update([
                'status' => 'transferida',
                'data_encerramento' => now(),
            ]);

            $this->registrarHistorico(
                $matricula,
                'transferencia',
                "Transferência realizada. Motivo: {$motivo}."
            );

            return $matricula;
        });
    }

    /**
     * Realizar rematrícula para novo ano letivo.
     */
    public function rematricular(Matricula $matriculaAnterior, int $novoAnoLetivo): Matricula
    {
        return DB::transaction(function () use ($matriculaAnterior, $novoAnoLetivo) {
            // Marca a anterior como rematriculada (encerrada neste ciclo)
            $matriculaAnterior->update([
                'status' => 'rematriculada',
                'data_encerramento' => now(),
            ]);

            // Cria a nova matrícula baseada na anterior
            $novaMatricula = Matricula::create([
                'aluno_id' => $matriculaAnterior->aluno_id,
                'escola_id' => $matriculaAnterior->escola_id,
                'turma_id' => null, // Rematrícula geralmente começa sem turma definida
                'ano_letivo' => $novoAnoLetivo,
                'tipo' => $matriculaAnterior->tipo,
                'status' => 'ativa',
                'data_matricula' => now(),
            ]);

            $this->registrarHistorico(
                $matriculaAnterior,
                'rematricula',
                "Aluno rematriculado para o ano {$novoAnoLetivo}. Nova matrícula ID: {$novaMatricula->id}."
            );

            $this->registrarHistorico(
                $novaMatricula,
                'criacao',
                "Nova matrícula gerada via processo de rematrícula do ano {$matriculaAnterior->ano_letivo}."
            );

            return $novaMatricula;
        });
    }

    /**
     * Registra uma entrada no histórico da matrícula.
     */
    public function registrarHistorico(Matricula $matricula, string $acao, string $descricao): MatriculaHistorico
    {
        return MatriculaHistorico::create([
            'matricula_id' => $matricula->id,
            'acao' => $acao,
            'descricao' => $descricao,
            'usuario_id' => Auth::id(),
            'created_at' => now(),
        ]);
    }

    /**
     * Listar matrículas com filtros.
     */
    public function listarMatriculas(array $filtros = []): LengthAwarePaginator
    {
        $query = Matricula::with(['aluno', 'escola', 'turma']);

        if (! empty($filtros['aluno_nome'])) {
            $query->whereHas('aluno', function ($q) use ($filtros) {
                $q->where('nome_completo', 'like', "%{$filtros['aluno_nome']}%");
            });
        }

        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (! empty($filtros['ano_letivo'])) {
            $query->where('ano_letivo', $filtros['ano_letivo']);
        }

        if (! empty($filtros['turma_id'])) {
            if ($filtros['turma_id'] === '__sem_turma') {
                $query->whereNull('turma_id');
            } else {
                $query->where('turma_id', $filtros['turma_id']);
            }
        }

        if (! empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (! empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        return $query->latest()->paginate(15);
    }

    /**
     * Obtém estatísticas de matrículas para uma escola.
     */
    public function obterStats(int $escolaId): array
    {
        $baseQuery = Matricula::where('escola_id', $escolaId);

        return [
            'ativas' => (clone $baseQuery)->where('status', 'ativa')->count(),
            'sem_turma' => (clone $baseQuery)->where('status', 'ativa')->whereNull('turma_id')->count(),
            'regular' => (clone $baseQuery)->where('tipo', 'regular')->count(),
            'aee' => (clone $baseQuery)->where('tipo', 'aee')->count(),
            'concluidas' => (clone $baseQuery)->where('status', 'concluida')->count(),
        ];
    }

    /**
     * Obtém anos letivos disponíveis para uma escola.
     */
    public function obterAnosLetivos(int $escolaId)
    {
        return Matricula::where('escola_id', $escolaId)
            ->select('ano_letivo')
            ->distinct()
            ->orderByDesc('ano_letivo')
            ->pluck('ano_letivo');
    }
}
