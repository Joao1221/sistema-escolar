<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReavaliacaoPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_reavaliacao' => ['required', 'date'],
            'progresso_observado' => ['nullable', 'string'],
            'dificuldades_persistentes' => ['nullable', 'string'],
            'ajuste_plano' => ['nullable', 'string'],
            'frequencia_nova' => ['nullable', 'in:semanal,quinzenal,mensal,outra'],
            'decisao' => ['required', 'in:manter_plano,ajustar_plano,suspender,encaminhar,encerrar'],
            'justificativa' => ['nullable', 'string'],
            'proxima_reavaliacao' => ['nullable', 'date'],
        ];
    }
}
