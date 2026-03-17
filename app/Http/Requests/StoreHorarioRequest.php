<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHorarioRequest extends FormRequest
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
            
            // Vamos receber um array "horarios" para adicionar múltiplas aulas de uma vez
            'horarios' => ['required', 'array', 'min:1'],
            'horarios.*.dia_semana' => ['required', 'integer', 'min:1', 'max:7'],
            'horarios.*.horario_inicial' => ['required', 'date_format:H:i'],
            'horarios.*.horario_final' => ['required', 'date_format:H:i', 'after:horarios.*.horario_inicial'],
            'horarios.*.disciplina_id' => ['required', 'exists:disciplinas,id'],
            'horarios.*.professor_id' => ['nullable', 'exists:funcionarios,id'],
            'horarios.*.ordem_aula' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'escola_id.required' => 'A escola é obrigatória.',
            'turma_id.required' => 'A turma é obrigatória.',
            'horarios.required' => 'Você precisa informar pelo menos um horário de aula.',
            'horarios.*.dia_semana.required' => 'O dia da semana é obrigatório.',
            'horarios.*.horario_inicial.required' => 'A hora inicial é obrigatória.',
            'horarios.*.horario_final.required' => 'A hora final é obrigatória.',
            'horarios.*.disciplina_id.required' => 'A disciplina é obrigatória.',
        ];
    }
}
