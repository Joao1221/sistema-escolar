<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJustificativaFaltaAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motivo' => ['required', 'string', 'min:8', 'max:1000'],
            'documento_comprobatorio' => ['nullable', 'string', 'max:255'],
        ];
    }
}
