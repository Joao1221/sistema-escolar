<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParametrosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('editar configuracoes');
    }

    public function rules(): array
    {
        return [
            'ano_letivo_vigente' => ['required', 'integer', 'min:2000', 'max:2100'],
            'dias_letivos_minimos' => ['required', 'integer', 'min:1'],
            'media_minima' => ['required', 'numeric', 'min:0', 'max:10'],
            'frequencia_minima' => ['required', 'integer', 'min:0', 'max:100'],
            'parametros_documentos' => ['nullable', 'string'],
            'parametros_upload' => ['nullable', 'string'],
        ];
    }
}
