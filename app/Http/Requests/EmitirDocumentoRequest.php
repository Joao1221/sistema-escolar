<?php

namespace App\Http\Requests;

use App\Support\DocumentosPortal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmitirDocumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $portal = DocumentosPortal::portalPorRota($this->route()?->getName());
        $tipo = (string) $this->route('tipo');
        $definicao = $portal ? DocumentosPortal::definicao($portal, $tipo) : null;

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
            'escola_id' => ['required', 'integer', Rule::exists('escolas', 'id')],
            'matricula_id' => ['required', 'integer', Rule::exists('matriculas', 'id')],
            'aluno_id' => ['required', 'integer', Rule::exists('alunos', 'id')],
            'diario_id' => ['required', 'integer', Rule::exists('diarios_professor', 'id')],
            'atendimento_id' => ['required', 'integer', Rule::exists('atendimentos_psicossociais', 'id')],
            'relatorio_id' => ['required', 'integer', Rule::exists('relatorios_tecnicos_psicossociais', 'id')],
            'encaminhamento_id' => ['required', 'integer', Rule::exists('encaminhamentos_psicossociais', 'id')],
            'titulo' => ['required', 'string', 'max:150'],
            'referencia' => ['nullable', 'string', 'max:150'],
            'destinatario' => ['required', 'string', 'max:150'],
            'assunto' => ['required', 'string', 'max:150'],
            'destino' => ['required', 'string', 'max:150'],
            'conteudo' => ['required', 'string', 'max:6000'],
            default => ['nullable', 'string', 'max:255'],
        };
    }
}
