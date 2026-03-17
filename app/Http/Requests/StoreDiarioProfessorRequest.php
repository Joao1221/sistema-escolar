<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiarioProfessorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('criar diarios');
    }

    public function rules(): array
    {
        return [
            'escola_id' => ['required', 'exists:escolas,id'],
            'turma_id' => ['required', 'exists:turmas,id'],
            'disciplina_id' => ['required', 'exists:disciplinas,id'],
            'ano_letivo' => ['required', 'integer', 'min:2024', 'max:2100'],
            'periodo_tipo' => ['required', 'in:bimestre,trimestre,semestre,anual,etapa'],
            'periodo_referencia' => ['required', 'string', 'max:30'],
            'observacoes_gerais' => ['nullable', 'string'],
        ];
    }
}
