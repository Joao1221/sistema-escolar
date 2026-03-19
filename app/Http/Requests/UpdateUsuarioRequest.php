<?php

namespace App\Http\Requests;

use App\Models\Funcionario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('editar usuario');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email,' . $this->usuario->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'exists:perfis,id'],
            'escolas' => ['nullable', 'array'],
            'escolas.*' => ['exists:escolas,id'],
            'funcionario_id' => [
                Rule::requiredIf((bool) $this->usuario->funcionario_id),
                'nullable',
                'integer',
                'exists:funcionarios,id',
                Rule::unique('usuarios', 'funcionario_id')->ignore($this->usuario->id),
            ],
            'ativo' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $funcionario = $this->funcionarioSelecionado();

        if (! $funcionario) {
            return;
        }

        $this->merge([
            'name' => $funcionario->nome,
            'email' => $funcionario->email,
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $funcionario = $this->funcionarioSelecionado();

            if (! $funcionario) {
                return;
            }

            if (blank($funcionario->email)) {
                $validator->errors()->add('funcionario_id', 'O funcionario selecionado precisa ter um e-mail cadastrado antes da vinculacao do usuario.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'funcionario_id.required' => 'Este usuario precisa permanecer vinculado a um funcionario cadastrado.',
            'funcionario_id.unique' => 'Este funcionario ja possui um usuario vinculado.',
        ];
    }

    private function funcionarioSelecionado(): ?Funcionario
    {
        $funcionarioId = $this->input('funcionario_id');

        if (! $funcionarioId) {
            return null;
        }

        return Funcionario::query()->find($funcionarioId);
    }
}
