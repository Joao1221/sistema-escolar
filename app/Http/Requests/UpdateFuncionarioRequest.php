<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFuncionarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('editar funcionario');
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:14', 'unique:funcionarios,cpf,' . $this->route('funcionario')->id],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cargo' => ['required', 'string', 'max:100'],
            'escolas' => ['required', 'array', 'min:1'],
            'escolas.*' => ['exists:escolas,id'],
            'ativo' => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            'escolas.required' => 'O funcionário deve estar vinculado a pelo menos uma escola.',
            'cpf.unique' => 'Este CPF já está cadastrado para outro funcionário.',
        ];
    }
}
