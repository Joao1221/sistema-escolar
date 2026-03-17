<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestão de Turmas</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase">Unidade: {{ Auth::user()->name }}</p>
        </div>
        @can('cadastrar turmas')
        <a href="{{ route('secretaria-escolar.turmas.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition" style="background-color: #059669;">
            + Nova Turma
        </a>
        @endcan
    </div>

    {{-- Filtros --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('secretaria-escolar.turmas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <x-input-label for="nome" :value="__('Nome da Turma')" />
                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="request('nome')" placeholder="Ex: Turma A" />
            </div>
            <div>
                <x-input-label for="modalidade_id" :value="__('Modalidade')" />
                <select id="modalidade_id" name="modalidade_id" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                    <option value="">Todas</option>
                    @foreach ($modalidades as $mod)
                        <option value="{{ $mod->id }}" {{ request('modalidade_id') == $mod->id ? 'selected' : '' }}>{{ $mod->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="ano_letivo" :value="__('Ano Letivo')" />
                <x-text-input id="ano_letivo" name="ano_letivo" type="number" class="mt-1 block w-full" :value="request('ano_letivo', date('Y'))" />
            </div>
            <div class="flex items-end space-x-2">
                <x-primary-button style="background-color: #059669;">Filtrar</x-primary-button>
                <a href="{{ route('secretaria-escolar.turmas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-semibold uppercase tracking-widest">Limpar</a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        {{-- Tabela --}}
        <div class="p-6 border-b border-gray-50 bg-gray-50/30">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Turma</th>
                            <th class="py-3 px-6">Modalidade / Série</th>
                            <th class="py-3 px-6 text-center">Turno</th>
                            <th class="py-3 px-6 text-center">Vagas</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($turmas as $turma)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800 uppercase">{{ $turma->nome }}</div>
                                <div class="text-xs text-gray-400">Ano: {{ $turma->ano_letivo }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm text-gray-700 font-medium">{{ $turma->modalidade->nome }}</div>
                                <div class="text-xs text-gray-400 capitalize">{{ $turma->serie_etapa }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold uppercase">{{ $turma->turno }}</span>
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-slate-700">
                                {{ $turma->vagas }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $turma->ativa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $turma->ativa ? 'ATIVA' : 'INATIVA' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('secretaria-escolar.turmas.show', $turma) }}" class="text-blue-600 hover:text-blue-900 font-bold">Ver</a>
                                @can('editar turmas')
                                <a href="{{ route('secretaria-escolar.turmas.edit', $turma) }}" class="text-emerald-600 hover:text-emerald-900 font-bold">Editar</a>
                                @endcan
                                @can('excluir turmas')
                                <form action="{{ route('secretaria-escolar.turmas.toggle', $turma) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $turma->ativa ? 'text-red-500' : 'text-green-500' }} font-bold text-[10px] uppercase">
                                        {{ $turma->ativa ? 'Inativar' : 'Ativar' }}
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 font-medium">Nenhuma turma encontrada nesta unidade.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Paginação --}}
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $turmas->appends(request()->query())->links() }}
        </div>
    </div>

</x-secretaria-escolar-layout>
