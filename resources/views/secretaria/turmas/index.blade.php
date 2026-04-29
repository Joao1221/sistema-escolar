<x-secretaria-layout>

    <x-slot name="header">
        <div class="relative z-10 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Consulta de Turmas') }}
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
        <form action="{{ route('secretaria.turmas.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <x-input-label for="escola_id" :value="__('Unidade Escolar')" />
                <select id="escola_id" name="escola_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todas as Escolas</option>
                    @foreach ($escolas as $escola)
                        <option value="{{ $escola->id }}" {{ request('escola_id') == $escola->id ? 'selected' : '' }}>{{ $escola->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="modalidade_id" :value="__('Modalidade')" />
                <select id="modalidade_id" name="modalidade_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todas as Modalidades</option>
                    @foreach ($modalidades as $mod)
                        <option value="{{ $mod->id }}" {{ request('modalidade_id') == $mod->id ? 'selected' : '' }}>{{ $mod->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="ano_letivo" :value="__('Ano Letivo')" />
                <x-text-input id="ano_letivo" name="ano_letivo" type="number" class="mt-1 block w-full" :value="request('ano_letivo', date('Y'))" />
            </div>
            <div class="flex flex-col gap-2 md:flex-row md:items-end">
                <x-primary-button class="justify-center bg-black hover:bg-gray-800">Filtrar</x-primary-button>
                <a href="{{ route('secretaria.turmas.index') }}" class="inline-flex justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-semibold uppercase tracking-widest">Limpar</a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
                <table class="min-w-[820px] w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Unidade Escolar</th>
                            <th class="py-3 px-6">Turma</th>
                            <th class="py-3 px-6">Modalidade</th>
                            <th class="py-3 px-6 text-center">Turno</th>
                            <th class="py-3 px-6 text-center">Vagas</th>
                            <th class="py-3 px-6 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($turmas as $turma)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="text-xs font-bold text-gray-800 uppercase">{{ $turma->escola->nome }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-indigo-700 uppercase">{{ $turma->nome }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $turma->serie_etapa }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-600 uppercase">
                                {{ $turma->modalidade->nome }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold uppercase">{{ $turma->turno }}</span>
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-slate-700">
                                {{ $turma->vagas }}
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a href="{{ route('secretaria.turmas.show', $turma) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded-lg">Ver Detalhes</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 font-medium italic underline decoration-indigo-200">Não existem turmas cadastradas com os filtros selecionados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $turmas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</x-secretaria-layout>
