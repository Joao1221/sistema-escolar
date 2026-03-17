<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreValidacaoPlanejamentoPeriodoDirecaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('validar planejamento por periodo pela direcao') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:validado,ajustes_solicitados'],
            'parecer' => ['required', 'string', 'min:10'],
            'observacoes_internas' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
