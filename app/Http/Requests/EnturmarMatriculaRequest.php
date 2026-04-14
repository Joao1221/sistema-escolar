<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnturmarMatriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'turma_id' => ['required', 'exists:turmas,id'],
        ];
    }
}
