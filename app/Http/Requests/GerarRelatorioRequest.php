<?php

namespace App\Http\Requests;

use App\Support\RelatoriosPortal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GerarRelatorioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $portal = RelatoriosPortal::portalPorRota($this->route()?->getName());
        $tipo = (string) $this->route('tipo');
        $definicao = $portal ? RelatoriosPortal::definicao($portal, $tipo) : null;

        if (! $definicao) {
            return [];
        }

        $rules = [];

        foreach ($definicao['campos'] as $campo) {
            $rules[$campo['nome']] = $this->regrasDoCampo($campo['nome']);
        }

        return $rules;
    }

    private function regrasDoCampo(string $campo): array
    {
        return match ($campo) {
            'escola_id' => ['nullable', 'integer', Rule::exists('escolas', 'id')],
            'turma_id' => ['nullable', 'integer', Rule::exists('turmas', 'id')],
            'modalidade_id' => ['nullable', 'integer', Rule::exists('modalidades_ensino', 'id')],
            'professor_id' => ['nullable', 'integer', Rule::exists('funcionarios', 'id')],
            'matricula_id' => ['required', 'integer', Rule::exists('matriculas', 'id')],
            'ano_letivo' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            default => ['nullable', 'string', 'max:255'],
        };
    }
}
