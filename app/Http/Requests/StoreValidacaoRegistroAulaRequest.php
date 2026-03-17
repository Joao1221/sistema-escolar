<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreValidacaoRegistroAulaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('validar aulas registradas') ?? false;
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
