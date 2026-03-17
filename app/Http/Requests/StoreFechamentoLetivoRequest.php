<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFechamentoLetivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'escola_id' => ['required', 'integer', 'exists:escolas,id'],
            'ano_letivo' => ['required', 'integer', 'between:2000,2100'],
            'status' => ['required', 'in:iniciado,concluido'],
            'resumo' => ['nullable', 'string', 'max:2000'],
            'observacoes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
