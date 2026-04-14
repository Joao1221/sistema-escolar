<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessaoAtendimentoPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_sessao' => ['required', 'date'],
            'hora_inicio' => ['nullable'],
            'hora_fim' => ['nullable'],
            'tipo_sessao' => ['required', 'in:avaliacao,intervencao,retorno,emergencial,acolhimento,devolutiva,reavaliacao'],
            'objetivo_sessao' => ['nullable', 'string'],
            'relato_sessao' => ['nullable', 'string'],
            'estrategias_utilizadas' => ['nullable', 'string'],
            'comportamento_observado' => ['nullable', 'string'],
            'evolucao_percebida' => ['nullable', 'string'],
            'encaminhamentos_definidos' => ['nullable', 'string'],
            'necessita_retorno' => ['nullable', 'boolean'],
            'proximo_passo' => ['nullable', 'string'],
            'status' => ['nullable', 'in:realizado,remarcado,faltou,cancelado'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
