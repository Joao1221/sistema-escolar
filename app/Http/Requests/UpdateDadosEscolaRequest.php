<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDadosEscolaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome_gestor' => ['required', 'string', 'max:70'],
            'cpf_gestor' => ['required', 'string', 'size:11'],
            'ato_posse_diretor' => ['required', 'string', 'max:30'],
            'nome' => ['required', 'string', 'max:255'],
            'qtd_salas' => ['required', 'integer', 'min:0'],
            'email' => ['nullable', 'email', 'max:70'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'endereco' => ['nullable', 'string', 'max:70'],
            'localidade' => ['nullable', 'string', 'max:70'],
            'cidade' => ['required', 'string', 'max:50'],
            'uf' => ['required', 'string', 'size:2'],
            'cep' => ['required', 'string', 'max:10'],
            'ato_criacao' => ['nullable', 'string', 'max:30'],
            'ato_autoriza' => ['nullable', 'string', 'max:30'],
            'ato_recon' => ['nullable', 'string', 'max:30'],
        ];
    }
}
