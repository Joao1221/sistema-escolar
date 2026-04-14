<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDevolutivaPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'destinatario' => ['required', 'in:familia,professor,coordenacao,direcao,funcionario,outro'],
            'nome_destinatario' => ['nullable', 'string', 'max:255'],
            'data_devolutiva' => ['required', 'date'],
            'resumo_devolutiva' => ['nullable', 'string'],
            'orientacoes' => ['nullable', 'string'],
            'encaminhamentos_combinados' => ['nullable', 'string'],
            'necessita_acompanhamento' => ['nullable', 'boolean'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
