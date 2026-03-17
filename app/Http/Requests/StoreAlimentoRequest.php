<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cadastrar alimentos');
    }

    public function rules(): array
    {
        return [
            'categoria_alimento_id' => ['required', 'exists:categorias_alimentos,id'],
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('alimentos', 'nome')->where(fn ($query) => $query->where('categoria_alimento_id', $this->input('categoria_alimento_id'))),
            ],
            'unidade_medida' => ['required', 'string', 'max:20'],
            'estoque_minimo' => ['nullable', 'numeric', 'min:0'],
            'controla_validade' => ['nullable', 'boolean'],
            'observacoes' => ['nullable', 'string'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
