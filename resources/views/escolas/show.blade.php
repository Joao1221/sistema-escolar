<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $escola->nome }}</h1>
            <p class="text-sm text-gray-500 mt-1">Detalhes da Unidade Escolar</p>
        </div>
        <a href="{{ route('secretaria.escolas.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar para Lista</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-800">

        {{-- Card Principal --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between border-b pb-4 mb-4">
                    <h3 class="text-lg font-bold text-blue-900">Informações Institucionais</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $escola->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $escola->ativo ? 'UNIDADE ATIVA' : 'UNIDADE INATIVA' }}
                    </span>
                </div>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome Oficial</dt>
                        <dd class="text-md font-bold">{{ $escola->nome }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CNPJ</dt>
                        <dd class="text-md font-bold">{{ $escola->cnpj ?? 'Não informado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                        <dd class="text-md font-bold">{{ $escola->email ?? 'Não informado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                        <dd class="text-md font-bold">{{ $escola->telefone ?? 'Não informado' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-blue-900 border-b pb-4 mb-4">Endereço e Localização</h3>
                <div class="space-y-4">
                    <p><span class="font-bold">Logradouro:</span> {{ $escola->endereco ?? 'Não informado' }}</p>
                    <div class="grid grid-cols-2 gap-4">
                        <p><span class="font-bold">Bairro:</span> {{ $escola->bairro ?? '-' }}</p>
                        <p><span class="font-bold">CEP:</span> {{ $escola->cep ?? '-' }}</p>
                    </div>
                    <p><span class="font-bold">Cidade/UF:</span> {{ $escola->cidade }} / {{ $escola->uf }}</p>
                </div>
            </div>
        </div>

        {{-- Sidebar lateral --}}
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-green-800 border-b pb-4 mb-4">Gestão Escolar</h3>
                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Diretor(a) / Gestor(a)</dt>
                        <dd class="text-md font-bold">{{ $escola->nome_gestor ?? 'Não definido' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CPF do Gestor</dt>
                        <dd class="text-md font-bold">{{ $escola->cpf_gestor ?? 'Não informado' }}</dd>
                    </div>
                </div>
            </div>

            @can('editar escola')
            <div class="bg-gray-50 p-6 rounded-2xl border border-dashed border-gray-300">
                <h4 class="text-sm font-bold text-gray-600 mb-4 uppercase tracking-widest text-center">Ações Administrativas</h4>
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('secretaria.escolas.edit', $escola) }}" class="w-full text-center py-2 bg-indigo-600 text-white rounded font-bold hover:bg-indigo-700 transition shadow-sm">
                        Editar Cadastro
                    </a>
                    @can('ativar inativar escola')
                    <form action="{{ route('secretaria.escolas.toggle', $escola) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full text-center py-2 border border-gray-300 bg-white text-gray-700 rounded font-bold hover:bg-red-50 hover:text-red-700 transition">
                            {{ $escola->ativo ? 'Inativar Escola' : 'Reativar Escola' }}
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            @endcan
        </div>

    </div>

</x-secretaria-layout>
