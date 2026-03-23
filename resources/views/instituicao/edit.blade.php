<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Editar Dados Institucionais</h1>
            <p class="text-sm text-gray-500 mt-1">Prefeitura e Secretaria de Educação</p>
        </div>
        <a href="{{ route('secretaria.instituicao.show') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6 text-gray-900">

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('secretaria.instituicao.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-base font-bold mb-4">Governo Municipal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nome_prefeitura" :value="__('Nome da Prefeitura')" />
                            <x-text-input id="nome_prefeitura" class="block mt-1 w-full" type="text" name="nome_prefeitura" :value="old('nome_prefeitura', $instituicao->nome_prefeitura)" />
                        </div>
                        <div>
                            <x-input-label for="cnpj_prefeitura" :value="__('CNPJ da Prefeitura')" />
                            <x-text-input id="cnpj_prefeitura" class="block mt-1 w-full" type="text" name="cnpj_prefeitura" :value="old('cnpj_prefeitura', $instituicao->cnpj_prefeitura)" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="nome_prefeito" :value="__('Nome do(a) Prefeito(a)')" />
                            <x-text-input id="nome_prefeito" class="block mt-1 w-full" type="text" name="nome_prefeito" :value="old('nome_prefeito', $instituicao->nome_prefeito)" />
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-base font-bold mb-4">Secretaria de Educação</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nome_secretaria" :value="__('Nome da Secretaria')" />
                            <x-text-input id="nome_secretaria" class="block mt-1 w-full" type="text" name="nome_secretaria" :value="old('nome_secretaria', $instituicao->nome_secretaria)" />
                        </div>
                        <div>
                            <x-input-label for="sigla_secretaria" :value="__('Sigla')" />
                            <x-text-input id="sigla_secretaria" class="block mt-1 w-full" type="text" name="sigla_secretaria" :value="old('sigla_secretaria', $instituicao->sigla_secretaria)" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="nome_secretario" :value="__('Nome do(a) Secretário(a)')" />
                            <x-text-input id="nome_secretario" class="block mt-1 w-full" type="text" name="nome_secretario" :value="old('nome_secretario', $instituicao->nome_secretario)" />
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-base font-bold mb-4">Contato e Endereço</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-input-label for="endereco" :value="__('Endereço Sede')" />
                            <x-text-input id="endereco" class="block mt-1 w-full" type="text" name="endereco" :value="old('endereco', $instituicao->endereco)" />
                        </div>
                        <div>
                            <x-input-label for="municipio" :value="__('Município')" />
                            <x-text-input id="municipio" class="block mt-1 w-full" type="text" name="municipio" :value="old('municipio', $instituicao->municipio)" />
                        </div>
                        <div>
                            <x-input-label for="uf" :value="__('UF')" />
                            <x-text-input id="uf" class="block mt-1 w-full" type="text" name="uf" maxlength="2" :value="old('uf', $instituicao->uf)" />
                        </div>
                        <div>
                            <x-input-label for="cep" :value="__('CEP')" />
                            <x-text-input id="cep" class="block mt-1 w-full" type="text" name="cep" :value="old('cep', $instituicao->cep)" />
                        </div>
                        <div>
                            <x-input-label for="telefone" :value="__('Telefone')" />
                            <x-text-input id="telefone" class="block mt-1 w-full" type="text" name="telefone" :value="old('telefone', $instituicao->telefone)" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="email" :value="__('E-mail Institucional')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $instituicao->email)" />
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-base font-bold mb-4">Logomarcas e Brasões <span class="text-sm font-normal text-gray-500">(JPG ou PNG)</span></h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ([['brasao', 'Brasão', $instituicao->brasao_url], ['logo_prefeitura', 'Logo Prefeitura', $instituicao->logo_prefeitura_url], ['logo_secretaria', 'Logo Secretaria', $instituicao->logo_secretaria_url]] as [$field, $label, $path])
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <x-input-label :for="$field" :value="$label" class="mb-2"/>
                            @if ($path)
                                <div class="mb-2 h-20 bg-white border flex items-center justify-center p-1 rounded">
                                    <img src="{{ $path }}" class="max-h-16 object-contain" alt="{{ $label }}">
                                </div>
                            @endif
                            <input type="file" :id="$field" name="{{ $field }}" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".jpg,.jpeg,.png">
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-base font-bold mb-4">Parametrizações para Documentos</h3>
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="textos_institucionais" :value="__('Textos Institucionais Padrão')" />
                            <textarea id="textos_institucionais" name="textos_institucionais" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('textos_institucionais', $instituicao->textos_institucionais) }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="assinaturas_cargos" :value="__('Cargos para Assinaturas')" />
                            <textarea id="assinaturas_cargos" name="assinaturas_cargos" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('assinaturas_cargos', $instituicao->assinaturas_cargos) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end border-t pt-6">
                    <x-primary-button>{{ __('Salvar Dados Institucionais') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

</x-secretaria-layout>
