<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestão de Matrículas</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase">Controle de ativação, enturmação e fluxos da unidade</p>
        </div>
        @can('cadastrar matrícula')
        <a href="{{ route('secretaria-escolar.matriculas.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition" style="background-color: #059669;">
            + Nova Matrícula
        </a>
        @endcan
    </div>

    {{-- Filtros --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('secretaria-escolar.matriculas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <x-input-label for="aluno_nome" :value="__('Nome do Aluno')" />
                <x-text-input id="aluno_nome" name="aluno_nome" type="text" class="mt-1 block w-full" :value="request('aluno_nome')" placeholder="Digite o nome..." />
            </div>
            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    @foreach (['ativa', 'concluida', 'cancelada', 'transferida', 'rematriculada'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ strtoupper($st) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="tipo" :value="__('Tipo')" />
                <select id="tipo" name="tipo" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    <option value="regular" {{ request('tipo') == 'regular' ? 'selected' : '' }}>REGULAR</option>
                    <option value="aee" {{ request('tipo') == 'aee' ? 'selected' : '' }}>AEE</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <x-primary-button style="background-color: #059669;">Filtrar</x-primary-button>
                <a href="{{ route('secretaria-escolar.matriculas.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-[10px] font-bold uppercase">Limpar</a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        {{-- Tabela --}}
        <div class="p-6 border-b border-gray-50 bg-gray-50/30">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Aluno / RGM</th>
                            <th class="py-3 px-6">Turma / Ano</th>
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
                                <div class="text-[10px] font-mono text-emerald-600">RGM: {{ $matricula->aluno->rgm }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-xs font-bold text-gray-700 uppercase">{{ $matricula->turma ? $matricula->turma->nome : 'SEM TURMA' }}</div>
                                <div class="text-[10px] text-gray-400">Ano Letivo: {{ $matricula->ano_letivo }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold {{ $matricula->tipo == 'regular' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
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
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('secretaria-escolar.matriculas.show', $matricula) }}" class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-2 py-1 rounded">Ver</a>
                                
                                @if ($matricula->status == 'ativa')
                                    @if (!$matricula->turma_id)
                                    <a href="{{ route('secretaria-escolar.matriculas.enturmar.form', $matricula) }}" class="text-emerald-600 hover:text-emerald-900 font-bold bg-emerald-50 px-2 py-1 rounded">Enturmar</a>
                                    @endif
                                    
                                    <a href="{{ route('secretaria-escolar.matriculas.transferir.form', $matricula) }}" class="text-orange-600 hover:text-orange-900 font-bold bg-orange-50 px-2 py-1 rounded">Transferir</a>
                                @endif

                                @if ($matricula->status == 'concluida')
                                <a href="{{ route('secretaria-escolar.matriculas.rematricular.form', $matricula) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-2 py-1 rounded">Rematricular</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 font-medium italic uppercase">Nenhuma matrícula encontrada para esta busca.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        {{-- Paginação --}}
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $matriculas->appends(request()->query())->links() }}
        </div>
    </div>

</x-secretaria-escolar-layout>
