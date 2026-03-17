<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaltaFuncionarioDirecaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'escola_id' => ['required', 'integer', 'exists:escolas,id'],
            'funcionario_id' => ['required', 'integer', 'exists:funcionarios,id'],
            'data_falta' => ['required', 'date'],
            'turno' => ['required', 'in:matutino,vespertino,noturno,integral'],
            'tipo_falta' => ['required', 'in:falta,atraso,saida_antecipada'],
            'justificada' => ['nullable', 'boolean'],
            'motivo' => ['required', 'string', 'min:5', 'max:180'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
