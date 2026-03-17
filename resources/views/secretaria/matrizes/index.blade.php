<x-secretaria-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center relative z-10">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Matrizes Curriculares') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 uppercase">Definição de grades por modalidade e série</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('secretaria.matrizes.panorama') }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-lg font-bold text-[10px] text-indigo-700 uppercase tracking-widest hover:bg-indigo-100 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Panorama da Rede
                </a>
                <a href="{{ route('secretaria.matrizes.create') }}" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm" style="background-color: black;">
                    + Nova Matriz
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 mb-6 p-6">
        <form action="{{ route('secretaria.matrizes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <x-input-label for="nome" :value="__('Nome da Matriz')" />
                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="request('nome')" placeholder="Digite o nome..." />
            </div>
            <div>
                <x-input-label for="modalidade_id" :value="__('Modalidade')" />
                <select id="modalidade_id" name="modalidade_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todas</option>
                    @foreach ($modalidades as $mod)
                        <option value="{{ $mod->id }}" {{ request('modalidade_id') == $mod->id ? 'selected' : '' }}>{{ $mod->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <x-primary-button class="bg-black hover:bg-gray-800">
                    {{ __('Filtrar') }}
                </x-primary-button>
                <a href="{{ route('secretaria.matrizes.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-semibold uppercase tracking-widest">
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                    <tr>
                        <th class="py-3 px-6">Nome / Ano</th>
                        <th class="py-3 px-6">Modalidade / Série</th>
                        <th class="py-3 px-6 text-center">Abrangência</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($matrizes as $matriz)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="font-bold text-gray-800 uppercase">{{ $matriz->nome }}</div>
                            <div class="text-xs text-gray-400 font-mono italic">Vigência: {{ $matriz->ano_vigencia }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm font-medium text-slate-700">{{ $matriz->modalidade->nome }}</div>
                            <div class="text-[10px] text-indigo-500 font-bold uppercase">{{ $matriz->serie_etapa }}</div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if ($matriz->escola_id)
                                <span class="text-[10px] bg-amber-50 text-amber-700 px-2 py-1 rounded border border-amber-100 uppercase font-bold">Unidade: {{ $matriz->escola->nome }}</span>
                            @else
                                <span class="text-[10px] bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100 uppercase font-bold tracking-tight">Rede Pública Municipal</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $matriz->ativa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $matriz->ativa ? 'ATIVA' : 'INATIVA' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('secretaria.matrizes.show', $matriz) }}" class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-2 py-1 rounded">Ver</a>
                            @can('gerenciar matrizes')
                            <a href="{{ route('secretaria.matrizes.edit', $matriz) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-2 py-1 rounded">Editar</a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 font-medium">Nenhuma matriz curricular cadastrada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($matrizes->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $matrizes->links() }}
        </div>
        @endif
    </div>
</x-secretaria-layout>
