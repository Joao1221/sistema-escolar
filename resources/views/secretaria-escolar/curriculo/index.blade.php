<x-secretaria-escolar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                    {{ __('Consulta de Matrizes Curriculares') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 uppercase tracking-wider">Visualização das grades vigentes para a unidade</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 mb-6 p-6">
        <form action="{{ route('secretaria-escolar.curriculo.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <x-input-label for="nome" :value="__('Nome da Matriz')" class="text-emerald-700 font-bold" />
                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full border-emerald-100 focus:border-emerald-500 focus:ring-emerald-500" :value="request('nome')" placeholder="Filtrar por nome..." />
            </div>
            <div>
                <x-input-label for="modalidade_id" :value="__('Modalidade')" class="text-emerald-700 font-bold" />
                <select id="modalidade_id" name="modalidade_id" class="mt-1 block w-full border-emerald-100 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm">
                    <option value="">Todas</option>
                    @foreach ($modalidades as $mod)
                        <option value="{{ $mod->id }}" {{ request('modalidade_id') == $mod->id ? 'selected' : '' }}>{{ $mod->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Filtrar</span>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                    <tr>
                        <th class="py-4 px-6">Nome / Código</th>
                        <th class="py-4 px-6 text-center">Modalidade / Série</th>
                        <th class="py-4 px-6 text-center">Carga Total</th>
                        <th class="py-4 px-6 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($matrizes as $matriz)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="py-5 px-6">
                            <div class="font-bold text-gray-800 uppercase tracking-tight">{{ $matriz->nome }}</div>
                            <div class="text-[10px] text-emerald-600 font-mono mt-0.5">Ano: {{ $matriz->ano_vigencia }}</div>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-2 py-1 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase">{{ $matriz->modalidade->nome }}</span>
                            <div class="text-[10px] text-gray-400 mt-1 uppercase font-semibold">{{ $matriz->serie_etapa }}</div>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-emerald-600 font-bold">{{ $matriz->disciplinas->sum('pivot.carga_horaria') }}h</span>
                        </td>
                        <td class="py-5 px-6 text-right">
                            <a href="{{ route('secretaria-escolar.curriculo.show', $matriz) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg text-xs font-bold transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Visualizar</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-400">Nenhuma matriz curricular encontrada para esta unidade.</td>
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
</x-secretaria-escolar-layout>
