<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCasoDisciplinarSigilosoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('registrar casos disciplinares sigilosos');
    }

    public function rules(): array
    {
        return [
            'aluno_id' => ['nullable', 'exists:alunos,id'],
            'funcionario_id' => ['nullable', 'exists:funcionarios,id'],
            'data_ocorrencia' => ['required', 'date'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao_sigilosa' => ['required', 'string'],
            'medidas_adotadas' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['aberto', 'em_acompanhamento', 'encerrado'])],
        ];
    }
}
