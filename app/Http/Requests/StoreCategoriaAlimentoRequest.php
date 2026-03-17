<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cadastrar categorias de alimentos');
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255', 'unique:categorias_alimentos,nome'],
            'descricao' => ['nullable', 'string'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
