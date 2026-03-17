<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Novo Funcionário</h1>
            <p class="text-sm text-gray-500 mt-1">Cadastrar servidor da rede municipal</p>
        </div>
        <a href="{{ route('secretaria.funcionarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6">
            <form action="{{ route('secretaria.funcionarios.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- Dados Pessoais --}}
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-base font-bold text-gray-800 mb-4">👤 Informações Pessoais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="nome" :value="__('Nome Completo')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>
                        <div>
                            <x-input-label for="cpf" :value="__('CPF')" />
                            <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf')" required placeholder="000.000.000-00" />
                            <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('E-mail')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                        <div>
                            <x-input-label for="telefone" :value="__('Telefone')" />
                            <x-text-input id="telefone" name="telefone" type="text" class="mt-1 block w-full" :value="old('telefone')" />
                            <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
                        </div>
                        <div>
                            <x-input-label for="cargo" :value="__('Cargo / Função')" />
                            <select id="cargo" name="cargo" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione...</option>
                                @foreach (['Professor', 'Diretor', 'Coordenador', 'Secretário Escolar', 'Nutricionista', 'Auxiliar de Serviços Gerais', 'Merendeira', 'Vigilante'] as $cargo)
                                    <option value="{{ $cargo }}" {{ old('cargo') === $cargo ? 'selected' : '' }}>{{ $cargo }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('cargo')" />
                        </div>
                    </div>
                </div>

                {{-- Vínculo Escolar --}}
                <div>
                    <h3 class="text-base font-bold text-gray-800 mb-2">🏫 Atribuição de Unidades Escolares</h3>
                    <p class="text-sm text-gray-500 mb-4">Selecione uma ou mais escolas onde o funcionário atuará:</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        @foreach ($escolas as $escola)
                        <label class="inline-flex items-center p-3 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                            <input type="checkbox" name="escolas[]" value="{{ $escola->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ is_array(old('escolas')) && in_array($escola->id, old('escolas')) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700 font-medium">{{ $escola->nome }}</span>
                        </label>
                        @endforeach
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('escolas')" />
                </div>

                <div class="flex items-center justify-end border-t pt-6">
                    <x-primary-button>{{ __('Cadastrar Funcionário') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

</x-secretaria-layout>
