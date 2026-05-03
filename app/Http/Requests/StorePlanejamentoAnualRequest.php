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
        $regrasUnidade = [
            'tema_gerador' => ['nullable', 'string', 'max:255'],
            'objetivos_aprendizagem' => ['nullable', 'string'],
            'habilidades_competencias' => ['nullable', 'string'],
            'conteudos' => ['nullable', 'string'],
            'metodologia' => ['nullable', 'string'],
            'estrategias_pedagogicas' => ['nullable', 'string'],
            'recursos_didaticos' => ['nullable', 'string'],
            'instrumentos_avaliacao' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],
            'referencias' => ['nullable', 'string'],
            'adequacoes_inclusao' => ['nullable', 'string'],
        ];

        return array_merge([
            'tema_gerador' => ['nullable', 'string', 'max:255'],
            'periodo_vigencia_inicio' => ['nullable', 'date'],
            'periodo_vigencia_fim' => ['nullable', 'date', 'after_or_equal:periodo_vigencia_inicio'],
            'objetivos_gerais' => ['nullable', 'string'],
            'conteudos' => ['nullable', 'string'],
            'metodologia' => ['nullable', 'string'],
            'instrumentos_avaliacao' => ['nullable', 'string'],
            'adequacoes_inclusao' => ['nullable', 'string'],
        ], [
            'unidades.1' => ['nullable', 'array'],
            'unidades.1.*' => ['nullable', 'string'],
            'unidades.2' => ['nullable', 'array'],
            'unidades.2.*' => ['nullable', 'string'],
            'unidades.3' => ['nullable', 'array'],
            'unidades.3.*' => ['nullable', 'string'],
            'unidades.4' => ['nullable', 'array'],
            'unidades.4.*' => ['nullable', 'string'],
        ]);
    }

    protected function prepareForValidation()
    {
        // Adicionar dados dummy para data_inicio e data_fim se não enviados
        if (!$this->has('unidades.1.data_inicio')) {
            $unidades = $this->get('unidades', []);
            foreach ([1, 2, 3, 4] as $u) {
                if (isset($unidades[$u])) {
                    $unidades[$u]['data_inicio'] = now()->startOfYear()->toDateString();
                    $unidades[$u]['data_fim'] = now()->endOfYear()->toDateString();
                }
            }
            $this->merge(['unidades' => $unidades]);
        }
    }
}
