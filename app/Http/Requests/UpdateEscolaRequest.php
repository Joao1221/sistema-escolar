<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEscolaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('editar escola');
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'inep' => [
                'required',
                'digits:8',
                Rule::unique('escolas', 'inep')->ignore($this->route('escola')->id),
            ],
            'cnpj' => ['nullable', 'string', 'max:18', 'unique:escolas,cnpj,' . $this->route('escola')->id],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cep' => ['nullable', 'string', 'max:9'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'uf' => ['nullable', 'string', 'size:2'],
            'nome_gestor' => ['nullable', 'string', 'max:255'],
            'cpf_gestor' => ['nullable', 'string', 'max:14'],
            'ativo' => ['boolean'],
        ];
    }
}
