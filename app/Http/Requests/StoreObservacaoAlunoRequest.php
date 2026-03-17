<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObservacaoAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('registrar observacoes pedagogicas');
    }

    public function rules(): array
    {
        return [
            'matricula_id' => ['required', 'exists:matriculas,id'],
            'data_observacao' => ['required', 'date'],
            'categoria' => ['required', 'in:pedagogica,comportamental,inclusao,acompanhamento'],
            'descricao' => ['required', 'string'],
            'encaminhamento' => ['nullable', 'string'],
            'destaque' => ['nullable', 'boolean'],
        ];
    }
}
