<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLancamentoAvaliativoCoordenacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('alterar notas e conceitos pedagogicos') ?? false;
    }

    public function rules(): array
    {
        return [
            'avaliacao_referencia' => ['required', 'string', 'max:255'],
            'valor_numerico' => ['nullable', 'numeric', 'between:0,100'],
            'conceito' => ['nullable', 'string', 'max:50'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
