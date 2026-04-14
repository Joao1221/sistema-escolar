<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EncerrarAtendimentoPsicossocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_encerramento' => ['nullable', 'date'],
            'motivo_encerramento' => ['nullable', 'string'],
            'resumo_encerramento' => ['nullable', 'string'],
            'orientacoes_finais' => ['nullable', 'string'],
        ];
    }
}
