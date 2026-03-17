<?php

namespace App\Services;

use App\Models\Funcionario;
use Illuminate\Support\Facades\DB;

class FuncionarioService
{
    /**
     * Lista funcionários com filtros.
     */
    public function listarFuncionarios(array $filtros = [])
    {
        $query = Funcionario::with('escolas');

        if (!empty($filtros['nome'])) {
            $query->where('nome', 'like', '%' . $filtros['nome'] . '%');
        }

        if (!empty($filtros['cpf'])) {
            $query->where('cpf', 'like', '%' . $filtros['cpf'] . '%');
        }

        if (!empty($filtros['cargo'])) {
            $query->where('cargo', $filtros['cargo']);
        }

        if (!empty($filtros['escola_id'])) {
            $query->whereHas('escolas', function ($q) use ($filtros) {
                $q->where('escolas.id', $filtros['escola_id']);
            });
        }

        return $query->orderBy('nome')->paginate(10);
    }

    /**
     * Cria um novo funcionário e vincula às escolas.
     */
    public function criarFuncionario(array $dados)
    {
        return DB::transaction(function () use ($dados) {
            $funcionario = Funcionario::create($dados);

            if (!empty($dados['escolas'])) {
                $funcionario->escolas()->sync($dados['escolas']);
            }

            return $funcionario;
        });
    }

    /**
     * Atualiza funcionário e seus vínculos.
     */
    public function atualizarFuncionario(Funcionario $funcionario, array $dados)
    {
        return DB::transaction(function () use ($funcionario, $dados) {
            $funcionario->update($dados);

            if (isset($dados['escolas'])) {
                $funcionario->escolas()->sync($dados['escolas']);
            }

            return $funcionario;
        });
    }

    /**
     * Alterna status do funcionário.
     */
    public function alternarStatus(Funcionario $funcionario)
    {
        $funcionario->ativo = !$funcionario->ativo;
        $funcionario->save();
        return $funcionario;
    }
}
