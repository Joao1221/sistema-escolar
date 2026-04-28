<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestão de Alunos</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase">Listagem completa e busca de estudantes.</p>
        </div>
        @can('criar aluno')
        <a href="{{ route('secretaria-escolar.alunos.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-emerald-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition" 
           style="background-color: #059669;">
            + Novo Aluno
        </a>
        @endcan
    </div>

    {{-- Filtros --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('secretaria-escolar.alunos.index') }}" method="GET" class="flex items-end gap-4">
            <div style="width: 200px;">
                <label for="turma_id" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1">Turma</label>
                <select id="turma_id" name="turma_id" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm text-sm py-2 px-3">
                    <option value="">Todas</option>
                    @foreach ($turmas as $turma)
                        <option value="{{ $turma->id }}" {{ request('turma_id') == $turma->id ? 'selected' : '' }}>
                            {{ $turma->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="width: 340px;">
                <label for="nome" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1">Nome do Aluno</label>
                <input type="text" id="nome" name="nome" value="{{ request('nome') }}" placeholder="Digite o nome..." class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div style="width: 200px;">
                <label for="rgm" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1">Matrícula (RGM)</label>
                <input type="text" id="rgm" name="rgm" value="{{ request('rgm') }}" placeholder="Ex: 20260001" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div style="width: 180px;">
                <label for="status" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1">Status</label>
                <select id="status" name="status" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm text-sm py-2 px-3">
                    <option value="">Todos</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="flex gap-2" style="width: 240px;">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition text-xs font-semibold uppercase tracking-widest whitespace-nowrap">
                    Filtrar
                </button>
                <a href="{{ route('secretaria-escolar.alunos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-xs font-semibold uppercase tracking-widest whitespace-nowrap">
                    Limpar
                </a>
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
                            <td class="py-4 px-6 text-center font-mono font-bold text-emerald-600">
                                {{ $aluno->rgm }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800 uppercase">{{ $aluno->nome_completo }}</div>
                                <div class="text-xs">{{ $aluno->cpf ?? 'Sem CPF' }} | {{ $aluno->data_nascimento->format('d/m/Y') }} ({{ $aluno->idade }} anos)</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm text-gray-700 uppercase">{{ $aluno->nome_mae }}</div>
                                <div class="text-xs text-gray-400">Resp: {{ $aluno->responsavel_nome }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $aluno->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $aluno->ativo ? 'ATIVO' : 'INATIVO' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('secretaria-escolar.alunos.show', $aluno) }}" class="text-blue-600 hover:text-blue-900 font-bold">Ver</a>
                                @can('editar aluno')
                                <a href="{{ route('secretaria-escolar.alunos.edit', $aluno) }}" class="text-emerald-600 hover:text-emerald-900 font-bold">Editar</a>
                                @endcan
                                @can('ativar inativar aluno')
                                <form action="{{ route('secretaria-escolar.alunos.toggle', $aluno) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $aluno->ativo ? 'text-red-500' : 'text-green-500' }} font-bold text-[10px] uppercase">
                                        {{ $aluno->ativo ? 'Inativar' : 'Ativar' }}
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 font-medium">Nenhum aluno encontrado nesta unidade.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        {{-- Paginação --}}
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $alunos->appends(request()->query())->links() }}
        </div>
    </div>

</x-secretaria-escolar-layout>
