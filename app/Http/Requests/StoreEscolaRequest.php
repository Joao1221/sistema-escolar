<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEscolaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('criar escola');
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:18', 'unique:escolas,cnpj'],
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
