<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovimentacaoAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->input('tipo') === 'saida'
            ? $this->user()->can('registrar saida de alimentos')
            : $this->user()->can('registrar entrada de alimentos');
    }

    public function rules(): array
    {
        return [
            'escola_id' => ['required', 'exists:escolas,id'],
            'alimento_id' => ['required', 'exists:alimentos,id'],
            'fornecedor_alimento_id' => ['nullable', 'exists:fornecedores_alimentos,id'],
            'tipo' => ['required', Rule::in(['entrada', 'saida'])],
            'quantidade' => ['required', 'numeric', 'gt:0'],
            'data_movimentacao' => ['required', 'date'],
            'data_validade' => ['nullable', 'date', 'after_or_equal:data_movimentacao'],
            'lote' => ['nullable', 'string', 'max:255'],
            'valor_unitario' => ['nullable', 'numeric', 'min:0'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
