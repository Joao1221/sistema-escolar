<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanejamentoSemanalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gerenciar planejamentos');
    }

    public function rules(): array
    {
        return [
            'data_inicio_semana' => ['required', 'date'],
            'data_fim_semana' => ['required', 'date', 'after_or_equal:data_inicio_semana'],
            'objetivos_semana' => ['required', 'string'],
            'conteudos_previstos' => ['required', 'string'],
            'estrategias' => ['nullable', 'string'],
            'avaliacao_prevista' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
