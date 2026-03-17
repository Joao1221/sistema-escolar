<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendenciaDocenteCoordenacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('gerenciar pendencias docentes') ?? false;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string', 'min:10'],
            'prazo' => ['nullable', 'date'],
            'status' => ['required', 'in:aberta,em_andamento,concluida'],
        ];
    }
}
