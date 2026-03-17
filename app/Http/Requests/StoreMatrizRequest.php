<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatrizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'modalidade_id' => ['required', 'exists:modalidades_ensino,id'],
            'serie_etapa' => ['required', 'string'],
            'escola_id' => ['nullable', 'exists:escolas,id'],
            'ano_vigencia' => ['required', 'integer', 'min:2000', 'max:2100'],
            'ativa' => ['boolean'],
            'disciplinas' => ['required', 'array', 'min:1'],
            'disciplinas.*.id' => ['required', 'exists:disciplinas,id'],
            'disciplinas.*.carga_horaria' => ['required', 'integer', 'min:1'],
            'disciplinas.*.obrigatoria' => ['boolean'],
        ];
    }
}
