<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLancamentoAvaliativoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gerenciar planejamentos');
    }

    public function rules(): array
    {
        return [
            'matricula_id' => ['required', 'integer', 'exists:matriculas,id'],
            'avaliacao_referencia' => ['required', 'string', 'max:255'],
            'valor_numerico' => ['nullable', 'numeric', 'between:0,100'],
            'conceito' => ['nullable', 'string', 'max:50'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
