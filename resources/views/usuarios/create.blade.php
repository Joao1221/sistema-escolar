<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Novo Usuario</h1>
            <p class="text-sm text-gray-500 mt-1">Criar conta de acesso ao sistema</p>
        </div>
        <a href="{{ route('secretaria.usuarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('secretaria.usuarios.store') }}" class="space-y-5 max-w-2xl">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Nome Completo')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('E-mail')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Senha')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <div>
                <x-input-label for="role" :value="__('Perfil de Acesso')" />
                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Sem perfil definido</option>
                    @foreach ($perfis as $perfil)
                        <option value="{{ $perfil->id }}" {{ old('role') == $perfil->id ? 'selected' : '' }}>{{ $perfil->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="funcionario_id" :value="__('Funcionario Vinculado')" />
                <select id="funcionario_id" name="funcionario_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Sem vinculo direto</option>
                    @foreach ($funcionarios as $funcionario)
                        <option value="{{ $funcionario->id }}" {{ old('funcionario_id') == $funcionario->id ? 'selected' : '' }}>
                            {{ $funcionario->nome }} - {{ $funcionario->cargo }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('funcionario_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="escolas" :value="__('Vinculo com Escolas (Segure CTRL para multipla selecao)')" />
                <select id="escolas" name="escolas[]" multiple class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm min-h-[100px]">
                    @foreach ($escolas as $escola)
                        <option value="{{ $escola->id }}" {{ (is_array(old('escolas')) && in_array($escola->id, old('escolas'))) ? 'selected' : '' }}>
                            {{ $escola->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="ativo" class="inline-flex items-center">
                    <input id="ativo" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="ativo" {{ old('ativo', true) ? 'checked' : '' }}>
                    <span class="ms-2 text-sm text-gray-600">{{ __('Usuario Ativo') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end border-t pt-5 space-x-4">
                <a href="{{ route('secretaria.usuarios.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition mr-8">
                    Cancelar
                </a>
                <x-primary-button style="background-color: #4f46e5;">{{ __('Salvar Usuario') }}</x-primary-button>
            </div>
        </form>
    </div>

</x-secretaria-layout>
