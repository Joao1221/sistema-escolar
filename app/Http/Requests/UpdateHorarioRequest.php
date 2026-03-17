<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHorarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('gerenciar horarios');
    }

    public function rules(): array
    {
        return [
            'escola_id' => ['required', 'exists:escolas,id'],
            'turma_id' => ['required', 'exists:turmas,id'],
            'dia_semana' => ['required', 'integer', 'min:1', 'max:7'],
            'horario_inicial' => ['required', 'date_format:H:i'],
            'horario_final' => ['required', 'date_format:H:i', 'after:horario_inicial'],
            'disciplina_id' => ['required', 'exists:disciplinas,id'],
            'professor_id' => ['nullable', 'exists:funcionarios,id'],
            'ordem_aula' => ['nullable', 'integer', 'min:1'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'escola_id.required' => 'A escola é obrigatória.',
            'turma_id.required' => 'A turma é obrigatória.',
            'dia_semana.required' => 'O dia da semana é obrigatório.',
            'horario_inicial.required' => 'A hora inicial é obrigatória.',
            'horario_final.required' => 'A hora final é obrigatória.',
            'disciplina_id.required' => 'A disciplina é obrigatória.',
        ];
    }
}
