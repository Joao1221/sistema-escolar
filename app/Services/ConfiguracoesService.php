<?php

namespace App\Services;

use App\Models\ModalidadeEnsino;
use App\Models\ParametroRede;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ConfiguracoesService
{
    /**
     * Obtém os parâmetros globais da rede (registro único).
     */
    public function obterParametros(): ParametroRede
    {
        return ParametroRede::firstOrCreate([], [
            'ano_letivo_vigente' => date('Y'),
            'dias_letivos_minimos' => 200,
            'media_minima' => 6.00,
            'frequencia_minima' => 75,
        ]);
    }

    /**
     * Atualiza os parâmetros globais da rede.
     */
    public function atualizarParametros(array $dados): ParametroRede
    {
        $parametros = $this->obterParametros();
        $parametros->update($dados);

        return $parametros;
    }

    /**
     * Lista todas as modalidades de ensino.
     */
    public function listarModalidades(): EloquentCollection
    {
        return ModalidadeEnsino::all();
    }

    /**
     * Cria ou atualiza uma modalidade de ensino.
     */
    public function salvarModalidade(array $dados, ?int $id = null): ModalidadeEnsino
    {
        if ($id) {
            $modalidade = ModalidadeEnsino::findOrFail($id);
            $modalidade->update($dados);

            return $modalidade;
        }

        return ModalidadeEnsino::create($dados);
    }

    /**
     * Alterna o status ativo/inativo de uma modalidade.
     */
    public function alternarStatusModalidade(int $id): ModalidadeEnsino
    {
        $modalidade = ModalidadeEnsino::findOrFail($id);
        $modalidade->ativo = ! $modalidade->ativo;
        $modalidade->save();

        return $modalidade;
    }
}
