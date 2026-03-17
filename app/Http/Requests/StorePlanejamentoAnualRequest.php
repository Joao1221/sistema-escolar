<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanejamentoAnualRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gerenciar planejamentos');
    }

    public function rules(): array
    {
        return [
            'tema_gerador' => ['nullable', 'string', 'max:255'],
            'periodo_vigencia_inicio' => ['nullable', 'date'],
            'periodo_vigencia_fim' => ['nullable', 'date', 'after_or_equal:periodo_vigencia_inicio'],
            'objetivos_gerais' => ['required', 'string'],
            'competencias_habilidades' => ['nullable', 'string'],
            'conteudos' => ['nullable', 'string'],
            'metodologia' => ['nullable', 'string'],
            'recursos_didaticos' => ['nullable', 'string'],
            'estrategias_pedagogicas' => ['nullable', 'string'],
            'estrategias_metodologicas' => ['nullable', 'string'],
            'instrumentos_avaliacao' => ['nullable', 'string'],
            'criterios_avaliacao' => ['nullable', 'string'],
            'cronograma_previsto' => ['nullable', 'string'],
            'referencias' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],
            'adequacoes_inclusao' => ['nullable', 'string'],
        ];
    }
}
