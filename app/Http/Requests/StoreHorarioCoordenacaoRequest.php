<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHorarioCoordenacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('cadastrar horarios pedagogicamente') ?? false;
    }

    public function rules(): array
    {
        return [
            'escola_id' => ['required', 'exists:escolas,id'],
            'turma_id' => ['required', 'exists:turmas,id'],
            'horarios' => ['required', 'array', 'min:1'],
            'horarios.*.dia_semana' => ['required', 'integer', 'min:1', 'max:7'],
            'horarios.*.horario_inicial' => ['required', 'date_format:H:i'],
            'horarios.*.horario_final' => ['required', 'date_format:H:i', 'after:horarios.*.horario_inicial'],
            'horarios.*.disciplina_id' => ['required', 'exists:disciplinas,id'],
            'horarios.*.professor_id' => ['nullable', 'exists:funcionarios,id'],
            'horarios.*.ordem_aula' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
