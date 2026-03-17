<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEncaminhamentoPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('registrar encaminhamentos psicossociais');
    }

    public function rules(): array
    {
        return [
            'tipo' => ['required', Rule::in(['interno', 'externo'])],
            'destino' => ['required', 'string', 'max:255'],
            'profissional_destino' => ['nullable', 'string', 'max:255'],
            'instituicao_destino' => ['nullable', 'string', 'max:255'],
            'motivo' => ['required', 'string'],
            'orientacoes_sigilosas' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['emitido', 'em_acompanhamento', 'concluido'])],
            'data_encaminhamento' => ['required', 'date'],
            'retorno_previsto_em' => ['nullable', 'date', 'after_or_equal:data_encaminhamento'],
        ];
    }
}
