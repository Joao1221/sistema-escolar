<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanoIntervencaoPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('registrar planos de intervencao psicossociais');
    }

    public function rules(): array
    {
        return [
            'objetivo_geral' => ['required', 'string'],
            'objetivos_especificos' => ['nullable', 'string'],
            'estrategias' => ['required', 'string'],
            'responsaveis_execucao' => ['nullable', 'string'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'status' => ['required', Rule::in(['ativo', 'em_acompanhamento', 'concluido'])],
            'observacoes_sigilosas' => ['nullable', 'string'],
        ];
    }
}
