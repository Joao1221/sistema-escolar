<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModalidadeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('editar configuracoes');
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'estrutura_avaliativa' => ['required', 'string', 'max:255'],
            'tipo_avaliacao' => ['required', 'string', 'max:255'],
            'carga_horaria_minima' => ['required', 'integer', 'min:0'],
            'ativo' => ['boolean'],
        ];
    }
}
