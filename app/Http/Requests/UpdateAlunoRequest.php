<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlunoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('editar aluno');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $alunoId = $this->route('aluno')->id ?? $this->route('aluno');

        return [
            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F,O',
            'cpf' => 'nullable|string|max:14|unique:alunos,cpf,' . $alunoId,
            'nis' => 'nullable|string|max:15',
            
            'nome_mae' => 'required|string|max:255',
            'nome_pai' => 'nullable|string|max:255',
            'responsavel_nome' => 'required|string|max:255',
            'responsavel_cpf' => 'required|string|max:14',
            'responsavel_telefone' => 'required|string|max:15',
            
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            
            'certidao_nascimento' => 'nullable|string|max:255',
            'rg_numero' => 'nullable|string|max:255',
            'rg_orgao' => 'nullable|string|max:255',
            
            'alergias' => 'nullable|string',
            'medicamentos' => 'nullable|string',
            'restricoes_alimentares' => 'nullable|string',
            'obs_saude' => 'nullable|string',
            
            'ativo' => 'nullable|boolean',
        ];
    }
}
