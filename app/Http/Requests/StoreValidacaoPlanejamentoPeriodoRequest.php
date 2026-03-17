<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreValidacaoPlanejamentoPeriodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('validar planejamento por periodo') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:validado,ajustes_solicitados'],
            'parecer' => ['required', 'string', 'min:10'],
            'observacoes_internas' => ['nullable', 'string'],
        ];
    }
}
