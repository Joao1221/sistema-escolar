<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDemandaPsicossocialEscolarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoPublico = (string) $this->input('tipo_publico');

        return [
            'escola_id' => ['required', 'exists:escolas,id'],
            'tipo_atendimento' => ['required', Rule::in(['psicologia', 'psicopedagogia', 'psicossocial'])],
            'tipo_publico' => ['required', Rule::in(['aluno', 'professor', 'funcionario', 'responsavel', 'coletivo'])],
            'aluno_id' => [Rule::requiredIf($tipoPublico === 'aluno'), 'nullable', 'exists:alunos,id'],
            'funcionario_id' => [Rule::requiredIf(in_array($tipoPublico, ['professor', 'funcionario'], true)), 'nullable', 'exists:funcionarios,id'],
            'responsavel_nome' => [Rule::requiredIf($tipoPublico === 'responsavel'), 'nullable', 'string', 'max:255'],
            'responsavel_telefone' => ['nullable', 'string', 'max:20'],
            'responsavel_vinculo' => [Rule::requiredIf($tipoPublico === 'responsavel'), 'nullable', 'string', 'max:100'],
            'motivo_inicial' => ['required', 'string'],
            'prioridade' => ['nullable', Rule::in(['baixa', 'media', 'alta', 'urgente'])],
            'data_solicitacao' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
