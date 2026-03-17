<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendenciaProfessorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gerenciar pendencias do professor');
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'origem' => ['required', 'in:diario,coordenacao,direcao,secretaria'],
            'prazo' => ['nullable', 'date'],
            'status' => ['required', 'in:aberta,em_andamento,concluida'],
        ];
    }
}
