<x-secretaria-layout>

    <x-slot name="header">
        <div class="relative z-10 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Consulta de Alunos') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 uppercase">Visão Consolidada da Rede Municipal</p>
            </div>
            <div class="self-start bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-xs font-bold uppercase border border-blue-100 italic md:self-auto">
                Visualização de Consulta (Somente Leitura)
            </div>
        </div>
    </x-slot>

    {{-- Filtros --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6 font-medium">
        <form action="{{ route('secretaria.alunos.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <x-input-label for="nome" :value="__('Nome do Aluno')" />
                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="request('nome')" placeholder="Digite o nome..." />
            </div>
            <div>
                <x-input-label for="rgm" :value="__('Matrícula (RGM)')" />
                <x-text-input id="rgm" name="rgm" type="text" class="mt-1 block w-full" :value="request('rgm')" placeholder="Ex: 20240001" />
            </div>
            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="flex flex-col gap-2 md:flex-row md:items-end">
                <x-primary-button class="justify-center bg-black hover:bg-gray-800">Filtrar</x-primary-button>
                <a href="{{ route('secretaria.alunos.index') }}" class="inline-flex justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-semibold uppercase tracking-widest">Limpar</a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
                <table class="min-w-[760px] w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                        <tr>
                            <th class="py-3 px-6 text-center">RGM</th>
                            <th class="py-3 px-6">Aluno</th>
                            <th class="py-3 px-6">Mãe / Responsável</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($alunos as $aluno)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 text-center font-mono font-bold text-indigo-600">
                                {{ $aluno->rgm }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800 uppercase">{{ $aluno->nome_completo }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5">{{ $aluno->data_nascimento->format('d/m/Y') }} ({{ $aluno->idade }} anos)</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-xs text-gray-700 uppercase font-medium">{{ $aluno->nome_mae }}</div>
                                <div class="text-[10px] text-gray-400 uppercase">Resp: {{ $aluno->responsavel_nome }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $aluno->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $aluno->ativo ? 'ATIVO' : 'INATIVO' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a href="{{ route('secretaria.alunos.show', $aluno) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded-lg">Ver Detalhes</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 font-medium italic">Nenhum aluno encontrado na rede.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $alunos->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</x-secretaria-layout>
