<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisciplinaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'codigo' => ['nullable', 'string', 'max:50', 'unique:disciplinas,codigo'],
            'carga_horaria_sugerida' => ['required', 'integer', 'min:0'],
            'ativo' => ['boolean'],
        ];
    }
}
