<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLancamentoFrequenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('lancar frequencia');
    }

    public function rules(): array
    {
        return [
            'registro_aula_id' => ['required', 'exists:registros_aula,id'],
            'frequencias' => ['required', 'array', 'min:1'],
            'frequencias.*.matricula_id' => ['required', 'exists:matriculas,id'],
            'frequencias.*.situacao' => ['required', 'in:presente,falta,falta_justificada,atraso'],
            'frequencias.*.justificativa' => ['nullable', 'string'],
            'frequencias.*.observacao' => ['nullable', 'string'],
        ];
    }
}
