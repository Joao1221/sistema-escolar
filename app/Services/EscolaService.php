<?php

namespace App\Services;

use App\Models\Escola;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EscolaService
{
    /**
     * Lista escolas com filtros.
     */
    public function listarEscolas(array $filtros = []): LengthAwarePaginator
    {
        $query = Escola::query();

        if (! empty($filtros['nome'])) {
            $query->where('nome', 'like', '%'.$filtros['nome'].'%');
        }

        if (isset($filtros['status']) && $filtros['status'] !== '') {
            $query->where('ativo', $filtros['status']);
        }

        return $query->orderBy('nome')->paginate(10);
    }

    /**
     * Cria uma nova escola.
     */
    public function criarEscola(array $dados): Escola
    {
        return Escola::create($dados);
    }

    /**
     * Atualiza uma escola existente.
     */
    public function atualizarEscola(Escola $escola, array $dados): Escola
    {
        $escola->update($dados);

        return $escola;
    }

    /**
     * Alterna o status da escola.
     */
    public function alternarStatus(Escola $escola): Escola
    {
        $escola->ativo = ! $escola->ativo;
        $escola->save();

        return $escola;
    }
}
