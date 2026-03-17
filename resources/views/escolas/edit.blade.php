<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Editar Escola</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $escola->nome }}</p>
        </div>
        <a href="{{ route('secretaria.escolas.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6">
            <form action="{{ route('secretaria.escolas.update', $escola) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Identificação --}}
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-base font-bold text-blue-800 mb-4">🏢 Identificação da Escola</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="nome" :value="__('Nome Oficial da Unidade')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $escola->nome)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>
                        <div>
                            <x-input-label for="cnpj" :value="__('CNPJ (opcional)')" />
                            <x-text-input id="cnpj" name="cnpj" type="text" class="mt-1 block w-full" :value="old('cnpj', $escola->cnpj)" />
                            <x-input-error class="mt-2" :messages="$errors->get('cnpj')" />
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('E-mail Institucional')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $escola->email)" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                        <div>
                            <x-input-label for="telefone" :value="__('Telefone de Contato')" />
                            <x-text-input id="telefone" name="telefone" type="text" class="mt-1 block w-full" :value="old('telefone', $escola->telefone)" />
                            <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
                        </div>
                    </div>
                </div>

                {{-- Localização --}}
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-base font-bold text-gray-800 mb-4">📍 Localização</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="cep" :value="__('CEP')" />
                            <x-text-input id="cep" name="cep" type="text" class="mt-1 block w-full" :value="old('cep', $escola->cep)" />
                            <x-input-error class="mt-2" :messages="$errors->get('cep')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="endereco" :value="__('Logradouro/Rua/Nº')" />
                            <x-text-input id="endereco" name="endereco" type="text" class="mt-1 block w-full" :value="old('endereco', $escola->endereco)" />
                            <x-input-error class="mt-2" :messages="$errors->get('endereco')" />
                        </div>
                        <div>
                            <x-input-label for="bairro" :value="__('Bairro')" />
                            <x-text-input id="bairro" name="bairro" type="text" class="mt-1 block w-full" :value="old('bairro', $escola->bairro)" />
                            <x-input-error class="mt-2" :messages="$errors->get('bairro')" />
                        </div>
                        <div>
                            <x-input-label for="cidade" :value="__('Cidade')" />
                            <x-text-input id="cidade" name="cidade" type="text" class="mt-1 block w-full" :value="old('cidade', $escola->cidade)" />
                            <x-input-error class="mt-2" :messages="$errors->get('cidade')" />
                        </div>
                        <div>
                            <x-input-label for="uf" :value="__('UF')" />
                            <x-text-input id="uf" name="uf" type="text" class="mt-1 block w-full" :value="old('uf', $escola->uf)" maxlength="2" />
                            <x-input-error class="mt-2" :messages="$errors->get('uf')" />
                        </div>
                    </div>
                </div>

                {{-- Gestão --}}
                <div>
                    <h3 class="text-base font-bold text-green-700 mb-4">👤 Dados do(a) Gestor(a) Escolar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="nome_gestor" :value="__('Nome Completo do Gestor(a)')" />
                            <x-text-input id="nome_gestor" name="nome_gestor" type="text" class="mt-1 block w-full" :value="old('nome_gestor', $escola->nome_gestor)" />
                            <x-input-error class="mt-2" :messages="$errors->get('nome_gestor')" />
                        </div>
                        <div>
                            <x-input-label for="cpf_gestor" :value="__('CPF do Gestor(a)')" />
                            <x-text-input id="cpf_gestor" name="cpf_gestor" type="text" class="mt-1 block w-full" :value="old('cpf_gestor', $escola->cpf_gestor)" />
                            <x-input-error class="mt-2" :messages="$errors->get('cpf_gestor')" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end border-t pt-6">
                    <x-primary-button>{{ __('Atualizar Escola') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

</x-secretaria-layout>
