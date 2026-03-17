<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLiberacaoPrazoProfessorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_lancamento' => ['required', 'in:aula,frequencia,notas_conceitos'],
            'data_limite' => ['required', 'date', 'after_or_equal:today'],
            'motivo' => ['required', 'string', 'min:6', 'max:180'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
