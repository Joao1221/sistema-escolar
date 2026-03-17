<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('editar categorias de alimentos');
    }

    public function rules(): array
    {
        $categoriaId = $this->route('categoria')->id;

        return [
            'nome' => ['required', 'string', 'max:255', Rule::unique('categorias_alimentos', 'nome')->ignore($categoriaId)],
            'descricao' => ['nullable', 'string'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
