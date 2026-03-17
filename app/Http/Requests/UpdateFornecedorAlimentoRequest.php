<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFornecedorAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('editar fornecedores de alimentos');
    }

    public function rules(): array
    {
        $fornecedorId = $this->route('fornecedor')->id;

        return [
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:18', Rule::unique('fornecedores_alimentos', 'cnpj')->ignore($fornecedorId)],
            'contato_nome' => ['nullable', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'uf' => ['nullable', 'string', 'size:2'],
            'observacoes' => ['nullable', 'string'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
