<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistroAulaCoordenacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('ajustar aulas pedagogicamente') ?? false;
    }

    public function rules(): array
    {
        return [
            'data_aula' => ['required', 'date'],
            'titulo' => ['required', 'string', 'max:255'],
            'conteudo_previsto' => ['nullable', 'string'],
            'conteudo_ministrado' => ['required', 'string'],
            'metodologia' => ['nullable', 'string'],
            'recursos_utilizados' => ['nullable', 'string'],
            'quantidade_aulas' => ['required', 'integer', 'min:1', 'max:10'],
            'aula_dada' => ['nullable', 'boolean'],
        ];
    }
}
