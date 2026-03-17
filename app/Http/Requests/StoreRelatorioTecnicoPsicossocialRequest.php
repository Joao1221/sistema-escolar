<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRelatorioTecnicoPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('emitir relatorios tecnicos psicossociais');
    }

    public function rules(): array
    {
        return [
            'tipo_relatorio' => ['required', Rule::in(['parecer_inicial', 'acompanhamento', 'encaminhamento', 'sintese'])],
            'titulo' => ['required', 'string', 'max:255'],
            'conteudo_sigiloso' => ['required', 'string'],
            'data_emissao' => ['required', 'date'],
            'observacoes_restritas' => ['nullable', 'string'],
        ];
    }
}
