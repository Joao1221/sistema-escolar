<x-secretaria-layout>

    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 uppercase">Consulta de Matrículas</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase italic">Visão Consolidada da Rede Municipal</p>
        </div>
        <div class="self-start bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-xs font-bold uppercase border border-indigo-100 italic md:self-auto">
            Somente Leitura
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6 font-medium">
        <form action="{{ route('secretaria.matriculas.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <x-input-label for="aluno_nome" :value="__('Nome do Aluno')" />
                <x-text-input id="aluno_nome" name="aluno_nome" type="text" class="mt-1 block w-full" :value="request('aluno_nome')" placeholder="Digite o nome..." />
            </div>
            <div>
                <x-input-label for="escola_id" :value="__('Escola')" />
                <select id="escola_id" name="escola_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todas as Escolas</option>
                    {{-- TODO: Injetar escolas aqui --}}
                </select>
            </div>
            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    @foreach (['ativa', 'concluida', 'cancelada', 'transferida', 'rematriculada'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ strtoupper($st) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-2 md:flex-row md:items-end">
                <x-primary-button style="background-color: #4f46e5;">Filtrar</x-primary-button>
                <a href="{{ route('secretaria.matriculas.index') }}" class="inline-flex justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-semibold uppercase tracking-widest">Limpar</a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
                <table class="min-w-[760px] w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Aluno</th>
                            <th class="py-3 px-6">Escola / Unidade</th>
                            <th class="py-3 px-6 text-center">Tipo</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($matriculas as $matricula)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800 uppercase">{{ $matricula->aluno->nome_completo }}</div>
                                <div class="text-[10px] font-mono text-indigo-600 uppercase">RGM: {{ $matricula->aluno->rgm }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-xs font-bold text-gray-700 uppercase">{{ $matricula->escola->nome }}</div>
                                <div class="text-[10px] text-gray-400">Turma: {{ $matricula->turma ? $matricula->turma->nome : 'NÃO ALOCADO' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $matricula->tipo == 'regular' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ strtoupper($matricula->tipo) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold 
                                    @if ($matricula->status == 'ativa') bg-green-100 text-green-800 
                                    @elseif ($matricula->status == 'cancelada') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ strtoupper($matricula->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('secretaria.matriculas.show', $matricula) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded-lg">Ver Detalhes</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 font-medium italic">Nenhuma matrícula identificada na rede.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $matriculas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</x-secretaria-layout>
