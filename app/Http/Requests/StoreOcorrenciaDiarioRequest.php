<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOcorrenciaDiarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('registrar ocorrencias pedagogicas');
    }

    public function rules(): array
    {
        return [
            'matricula_id' => ['nullable', 'exists:matriculas,id'],
            'data_ocorrencia' => ['required', 'date'],
            'tipo' => ['required', 'in:disciplinar,pedagogica,convivencia,encaminhamento'],
            'descricao' => ['required', 'string'],
            'providencias' => ['nullable', 'string'],
            'status' => ['required', 'in:aberta,em_acompanhamento,encerrada'],
        ];
    }
}
