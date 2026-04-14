<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTriagemPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resumo_caso' => ['nullable', 'string'],
            'sinais_observados' => ['nullable', 'string'],
            'historico_breve' => ['nullable', 'string'],
            'urgencia' => ['nullable', 'in:baixa,media,alta,critica'],
            'risco_identificado' => ['nullable', 'boolean'],
            'descricao_risco' => ['nullable', 'string'],
            'nivel_sigilo' => ['nullable', 'in:normal,reforcado'],
            'decisao' => ['required', 'in:iniciar_atendimento,observar,encaminhar_externo,devolver_pedagogico,encerrar_sem_atendimento'],
            'justificativa_decisao' => ['nullable', 'string'],
            'profissional_responsavel_id' => ['nullable', 'exists:funcionarios,id'],
            'data_triagem' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
