<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatriculaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('cadastrar matrícula');
    }

    public function rules(): array
    {
        return [
            'aluno_id' => 'required|exists:alunos,id',
            'turma_id' => 'nullable|exists:turmas,id',
            'ano_letivo' => 'required|integer|min:2024',
            'tipo' => 'required|in:regular,aee',
            'matricula_regular_id' => 'nullable|exists:matriculas,id',
            'data_matricula' => 'required|date',
            'observacoes' => 'nullable|string',
        ];
    }
}
