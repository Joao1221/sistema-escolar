<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dados Institucionais</h1>
            <p class="text-sm text-gray-500 mt-1">Informações da rede municipal de ensino</p>
        </div>
        @can('editar instituicao')
        <a href="{{ route('secretaria.instituicao.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition" style="background-color: #4f46e5;">
            Editar Cadastro
        </a>
        @endcan
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Logos --}}
        <div class="col-span-1 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 flex flex-col items-center">
                <h3 class="text-base font-bold mb-4 w-full border-b pb-2">Brasão</h3>
                @if ($instituicao->brasao_path)
                    <img src="{{ asset('storage/' . $instituicao->brasao_path) }}" alt="Brasão" class="max-h-40 object-contain rounded">
                @else
                    <div class="h-40 w-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 rounded">Sem Imagem</div>
                @endif
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 flex flex-col items-center">
                <h3 class="text-base font-bold mb-4 w-full border-b pb-2">Logo Prefeitura</h3>
                @if ($instituicao->logo_prefeitura_path)
                    <img src="{{ asset('storage/' . $instituicao->logo_prefeitura_path) }}" alt="Logo Prefeitura" class="max-h-32 object-contain rounded">
                @else
                    <div class="h-32 w-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 rounded">Sem Imagem</div>
                @endif
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 flex flex-col items-center">
                <h3 class="text-base font-bold mb-4 w-full border-b pb-2">Logo Secretaria</h3>
                @if ($instituicao->logo_secretaria_path)
                    <img src="{{ asset('storage/' . $instituicao->logo_secretaria_path) }}" alt="Logo Secretaria" class="max-h-32 object-contain rounded">
                @else
                    <div class="h-32 w-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 rounded">Sem Imagem</div>
                @endif
            </div>
        </div>

        {{-- Dados --}}
        <div class="col-span-1 md:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Governo Municipal</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">Prefeitura</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->nome_prefeitura ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">CNPJ</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->cnpj_prefeitura ?: '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 font-semibold uppercase">Prefeito(a)</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->nome_prefeito ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Secretaria de Educação</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 font-semibold uppercase">Secretaria</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->nome_secretaria ?: '-' }} ({{ $instituicao->sigla_secretaria ?: '-' }})</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 font-semibold uppercase">Secretário(a)</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->nome_secretario ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Contato e Endereço</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 font-semibold uppercase">Endereço Sede</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->endereco ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">Município / UF</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->municipio ?: '-' }}{{ $instituicao->uf ? ' - '.$instituicao->uf : '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">Telefone</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->telefone ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">E-mail</p>
                        <p class="text-gray-900 font-medium">{{ $instituicao->email ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-secretaria-layout>
