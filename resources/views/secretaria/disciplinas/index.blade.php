<x-secretaria-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center relative z-10">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Cadastro de Disciplinas') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 uppercase">Gestão global de componentes curriculares</p>
            </div>
            <a href="{{ route('secretaria.disciplinas.create') }}" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm" style="background-color: black;">
                + Nova Disciplina
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 mb-6 p-6">
        <form action="{{ route('secretaria.disciplinas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="nome" :value="__('Nome da Disciplina')" />
                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="request('nome')" placeholder="Digite o nome..." />
            </div>
            <div>
                <x-input-label for="codigo" :value="__('Código')" />
                <x-text-input id="codigo" name="codigo" type="text" class="mt-1 block w-full" :value="request('codigo')" placeholder="Ex: MAT01" />
            </div>
            <div class="flex items-end">
                <x-primary-button class="bg-black hover:bg-gray-800">
                    {{ __('Filtrar') }}
                </x-primary-button>
                <a href="{{ route('secretaria.disciplinas.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-semibold uppercase tracking-widest">
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
                        <th class="py-3 px-6">Código</th>
                        <th class="py-3 px-6">Disciplina</th>
                        <th class="py-3 px-6 text-center">Carga Horária (Sugerida)</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($disciplinas as $disciplina)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 font-mono text-xs text-indigo-600">{{ $disciplina->codigo ?? '-' }}</td>
                        <td class="py-4 px-6 font-bold text-gray-800 uppercase">{{ $disciplina->nome }}</td>
                        <td class="py-4 px-6 text-center italic">{{ $disciplina->carga_horaria_sugerida }}h</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $disciplina->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $disciplina->ativo ? 'ATIVA' : 'INATIVA' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                            @can('gerenciar disciplinas')
                            <a href="{{ route('secretaria.disciplinas.edit', $disciplina) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-2 py-1 rounded">Editar</a>
                            <form action="{{ route('secretaria.disciplinas.toggle', $disciplina) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $disciplina->ativo ? 'text-red-500' : 'text-green-500' }} font-bold text-[10px] uppercase bg-gray-50 px-2 py-1 rounded border border-gray-100">
                                    {{ $disciplina->ativo ? 'Inativar' : 'Ativar' }}
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 font-medium">Nenhuma disciplina cadastrada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($disciplinas->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $disciplinas->links() }}
        </div>
        @endif
    </div>
</x-secretaria-layout>
