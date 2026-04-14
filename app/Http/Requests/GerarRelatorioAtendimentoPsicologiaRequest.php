<?php

namespace App\Http\Requests;

use App\Services\PsicossocialService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GerarRelatorioAtendimentoPsicologiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $opcoesRelatorio = app(PsicossocialService::class)->opcoesRelatorioAtendimentos($this->user());
        $tiposRelatorio = array_keys($opcoesRelatorio['tipos_relatorio'] ?? []);
        $tiposAtendimento = array_keys($opcoesRelatorio['tipos_atendimento'] ?? []);
        $status = array_keys($opcoesRelatorio['status'] ?? []);
        $campos = array_keys($opcoesRelatorio['campos'] ?? []);

        return [
            'tipo_relatorio' => ['nullable', Rule::in($tiposRelatorio)],
            'escola_id' => ['nullable', 'integer', Rule::exists('escolas', 'id')],
            'profissional_id' => ['nullable', 'integer', Rule::exists('funcionarios', 'id'), 'required_if:tipo_relatorio,por_profissional'],
            'data_inicio' => ['nullable', 'date', 'required_if:tipo_relatorio,por_periodo'],
            'data_fim' => ['nullable', 'date', 'required_if:tipo_relatorio,por_periodo', 'after_or_equal:data_inicio'],
            'mes' => ['nullable', 'integer', 'between:1,12', 'required_if:tipo_relatorio,geral_mes'],
            'ano' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'tipo_atendimento' => ['nullable', Rule::in($tiposAtendimento)],
            'status' => ['nullable', Rule::in($status)],
            'campos' => ['nullable', 'array'],
            'campos.*' => ['string', Rule::in($campos)],
        ];
    }
}
