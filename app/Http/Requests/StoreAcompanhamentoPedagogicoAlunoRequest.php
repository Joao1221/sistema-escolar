<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcompanhamentoPedagogicoAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('acompanhar rendimento pedagogico') ?? false;
    }

    public function rules(): array
    {
        return [
            'nivel_rendimento' => ['required', 'in:adequado,em_atencao,defasado,critico'],
            'situacao_risco' => ['required', 'in:baixo,moderado,alto,critico'],
            'indicativos_aprendizagem' => ['required', 'string', 'min:10'],
            'fatores_risco' => ['nullable', 'string'],
            'encaminhamentos' => ['nullable', 'string'],
            'precisa_intervencao' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'precisa_intervencao' => $this->boolean('precisa_intervencao'),
        ]);
    }
}
