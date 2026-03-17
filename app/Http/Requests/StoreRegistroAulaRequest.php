<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistroAulaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('registrar aulas');
    }

    public function rules(): array
    {
        return [
            'data_aula' => ['required', 'date'],
            'titulo' => ['required', 'string', 'max:160'],
            'conteudo_previsto' => ['nullable', 'string'],
            'conteudo_ministrado' => ['required', 'string'],
            'metodologia' => ['nullable', 'string'],
            'recursos_utilizados' => ['nullable', 'string'],
            'horario_aula_id' => ['nullable', 'exists:horario_aulas,id'],
            'quantidade_aulas' => ['required', 'integer', 'min:1', 'max:10'],
            'aula_dada' => ['nullable', 'boolean'],
        ];
    }
}
