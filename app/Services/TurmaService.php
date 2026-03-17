<?php

namespace App\Services;

use App\Models\Turma;
use Illuminate\Support\Facades\DB;

class TurmaService
{
    /**
     * Lista turmas com filtros.
     */
    public function listarTurmas(array $filtros = [], int $paginacao = 10)
    {
        $query = Turma::with(['escola', 'modalidade']);

        if (!empty($filtros['nome'])) {
            $query->where('nome', 'like', '%' . $filtros['nome'] . '%');
        }

        if (!empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (!empty($filtros['modalidade_id'])) {
            $query->where('modalidade_id', $filtros['modalidade_id']);
        }

        if (!empty($filtros['ano_letivo'])) {
            $query->where('ano_letivo', $filtros['ano_letivo']);
        }

        if (isset($filtros['ativa'])) {
            $query->where('ativa', $filtros['ativa']);
        }

        return $query->orderBy('ano_letivo', 'desc')
                    ->orderBy('nome')
                    ->paginate($paginacao);
    }

    /**
     * Cria uma nova turma.
     */
    public function criarTurma(array $dados)
    {
        return Turma::create($dados);
    }

    /**
     * Atualiza uma turma.
     */
    public function atualizarTurma(Turma $turma, array $dados)
    {
        return $turma->update($dados);
    }

    /**
     * Inativa uma turma.
     */
    public function toggleStatus(Turma $turma)
    {
        $turma->ativa = !$turma->ativa;
        return $turma->save();
    }
}
