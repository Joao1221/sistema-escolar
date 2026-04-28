<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Enums\TipoPublicoPsicossocial;

class StoreAtendimentoPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('registrar atendimentos psicossociais');
    }

    public function rules(): array
    {
        $tipoPublico = $this->input('tipo_publico');
        $tiposPublicoValidos = collect(TipoPublicoPsicossocial::cases())->map(fn ($e) => $e->value)->all();

        return [
            'escola_id' => ['required', 'exists:escolas,id'],
            'profissional_responsavel_id' => ['nullable', 'exists:funcionarios,id'],
            'tipo_publico' => ['required', Rule::in($tiposPublicoValidos)],
            'aluno_id' => [Rule::requiredIf(in_array($tipoPublico, ['aluno', 'responsavel'], true)), 'nullable', 'exists:alunos,id'],
            'funcionario_id' => [Rule::requiredIf(in_array($tipoPublico, ['professor', 'funcionario'], true)), 'nullable', 'exists:funcionarios,id'],
            'responsavel_existente_id' => ['nullable', 'exists:atendidos_externos,id'],
            'responsavel_nome' => [Rule::requiredIf($tipoPublico === 'responsavel' && ! $this->filled('responsavel_existente_id')), 'nullable', 'string', 'max:255'],
            'responsavel_tipo_vinculo' => [Rule::requiredIf($tipoPublico === 'responsavel' && ! $this->filled('responsavel_existente_id')), 'nullable', Rule::in(['pai', 'mae', 'responsavel', 'outro'])],
            'responsavel_cpf' => ['nullable', 'string', 'max:20'],
            'responsavel_telefone' => ['nullable', 'string', 'max:30'],
            'responsavel_email' => ['nullable', 'email', 'max:255'],
            'tipo_atendimento' => ['required', Rule::in(['psicologia', 'psicopedagogia', 'psicossocial'])],
            'natureza' => ['required', Rule::in(['agendado', 'retorno', 'emergencial', 'acolhimento'])],
            'status' => ['required', Rule::in(['agendado', 'realizado', 'cancelado', 'faltou'])],
            'data_agendada' => ['required', 'date'],
            'data_realizacao' => ['nullable', 'date', 'after_or_equal:data_agendada'],
            'local_atendimento' => ['nullable', 'string', 'max:255'],
            'motivo_demanda' => ['required', 'string'],
            'resumo_sigiloso' => ['nullable', 'string'],
            'observacoes_restritas' => ['nullable', 'string'],
            'nivel_sigilo' => ['required', Rule::in(['restrito', 'muito_restrito'])],
            'requer_acompanhamento' => ['nullable', 'boolean'],
        ];
    }
}
