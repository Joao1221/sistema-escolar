<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCardapioDiarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('lancar cardapio diario');
    }

    public function rules(): array
    {
        return [
            'escola_id' => ['required', 'exists:escolas,id'],
            'data_cardapio' => [
                'required',
                'date',
                Rule::unique('cardapios_diarios', 'data_cardapio')->where(fn ($query) => $query->where('escola_id', $this->input('escola_id'))),
            ],
            'observacoes' => ['nullable', 'string'],
            'itens' => ['required', 'array', 'min:1'],
            'itens.*.alimento_id' => ['nullable', 'exists:alimentos,id'],
            'itens.*.refeicao' => ['nullable', 'string', 'max:30'],
            'itens.*.quantidade_prevista' => ['nullable', 'numeric', 'min:0'],
            'itens.*.observacoes' => ['nullable', 'string'],
        ];
    }
}
