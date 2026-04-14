<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandaPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'escola_id' => ['required', 'exists:escolas,id'],
            'origem_demanda' => ['required', 'in:coordenacao,direcao,professor,familia,triagem_interna,demanda_espontanea,outro'],
            'tipo_publico' => ['required', 'in:aluno,professor,funcionario,responsavel,coletivo'],
            'aluno_id' => ['nullable', 'exists:alunos,id'],
            'funcionario_id' => ['nullable', 'exists:funcionarios,id'],
            'responsavel_nome' => ['nullable', 'string', 'max:255'],
            'responsavel_telefone' => ['nullable', 'string', 'max:20'],
            'responsavel_vinculo' => ['nullable', 'string', 'max:100'],
            'tipo_atendimento' => ['nullable', 'in:psicologia,psicopedagogia,psicossocial'],
            'motivo_inicial' => ['required', 'string'],
            'prioridade' => ['nullable', 'in:baixa,media,alta,urgente'],
            'data_solicitacao' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
