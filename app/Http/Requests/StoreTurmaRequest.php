<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTurmaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cadastrar turmas');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'escola_id' => $this->input('escola_id', $this->user()?->escola_id),
        ]);
    }

    public function rules(): array
    {
        return [
            'escola_id' => 'required|exists:escolas,id',
            'modalidade_id' => 'required|exists:modalidades_ensino,id',
            'serie_etapa' => 'required|string|max:100',
            'nome' => 'required|string|max:100',
            'turno' => 'required|in:Matutino,Vespertino,Noturno,Integral',
            'ano_letivo' => 'required|integer|min:2020|max:2100',
            'vagas' => 'required|integer|min:0',
            'matriz_id' => 'nullable|exists:matrizes_curriculares,id',
            'is_multisseriada' => 'nullable|boolean',
            'ativa' => 'nullable|boolean',
        ];
    }
}
