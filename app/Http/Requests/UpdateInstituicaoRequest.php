<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstituicaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('editar instituicao');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome_prefeitura' => ['nullable', 'string', 'max:255'],
            'cnpj_prefeitura' => ['nullable', 'string', 'max:20'],
            'nome_prefeito' => ['nullable', 'string', 'max:255'],
            'nome_secretaria' => ['nullable', 'string', 'max:255'],
            'sigla_secretaria' => ['nullable', 'string', 'max:50'],
            'nome_secretario' => ['nullable', 'string', 'max:255'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'municipio' => ['nullable', 'string', 'max:100'],
            'uf' => ['nullable', 'string', 'size:2'],
            'cep' => ['nullable', 'string', 'max:20'],
            'brasao' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'logo_prefeitura' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'logo_secretaria' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'textos_institucionais' => ['nullable', 'string'],
            'assinaturas_cargos' => ['nullable', 'string'],
        ];
    }
}
