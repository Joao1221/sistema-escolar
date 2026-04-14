<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RematricularMatriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ano_letivo' => ['required', 'integer', 'min:2024'],
        ];
    }
}
