<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHorarioCoordenacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $usuario = $this->user();

        return ($usuario?->can('editar horarios pedagogicamente') ?? false)
            || ($usuario?->can('reorganizar horarios pedagogicamente') ?? false);
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
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
