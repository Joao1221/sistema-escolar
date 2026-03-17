<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanejamentoPeriodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gerenciar planejamentos');
    }

    public function rules(): array
    {
        return [
            'tipo_planejamento' => ['required', 'in:semanal,quinzenal,mensal,semestral'],
            'periodo_referencia' => ['nullable', 'string', 'max:255'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['required', 'date', 'after_or_equal:data_inicio'],
            'objetivos_aprendizagem' => ['required', 'string'],
            'habilidades_competencias' => ['nullable', 'string'],
            'conteudos' => ['required', 'string'],
            'metodologia' => ['nullable', 'string'],
            'recursos_didaticos' => ['nullable', 'string'],
            'estrategias_pedagogicas' => ['nullable', 'string'],
            'instrumentos_avaliacao' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],
            'adequacoes_inclusao' => ['nullable', 'string'],
        ];
    }
}
