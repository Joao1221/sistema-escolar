<x-secretaria-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center relative z-10">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Gestão de Escolas') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Unidades escolares da rede municipal</p>
            </div>
            @can('criar escola')
            <a href="{{ route('secretaria.escolas.create') }}" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                + Nova Escola
            </a>
            @endcan
        </div>
    </x-slot>

    {{-- Filtros --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('secretaria.escolas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="nome" :value="__('Nome da Escola')" />
                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="request('nome')" placeholder="Digite o nome..." />
            </div>
            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativa</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativa</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <x-primary-button type="submit">Filtrar</x-primary-button>
                <a href="{{ route('secretaria.escolas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-semibold uppercase tracking-widest">Limpar</a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Escola</th>
                            <th class="py-3 px-6 text-center">Gestor(a)</th>
                            <th class="py-3 px-6 text-center">Cidade/UF</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($escolas as $escola)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800">{{ $escola->nome }}</div>
                                <div class="text-xs">{{ $escola->cnpj ?? 'CNPJ não informado' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">{{ $escola->nome_gestor ?? '-' }}</td>
                            <td class="py-4 px-6 text-center uppercase">{{ $escola->cidade }} / {{ $escola->uf }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $escola->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $escola->ativo ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('secretaria.escolas.show', $escola) }}" class="text-blue-600 hover:text-blue-900 font-bold">Ver</a>
                                @can('editar escola')
                                <a href="{{ route('secretaria.escolas.edit', $escola) }}" class="text-indigo-600 hover:text-indigo-900 font-bold ml-2">Editar</a>
                                @endcan
                                @can('ativar inativar escola')
                                <form action="{{ route('secretaria.escolas.toggle', $escola) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $escola->ativo ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} font-bold text-xs uppercase cursor-pointer">
                                        {{ $escola->ativo ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">Nenhuma escola encontrada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $escolas->links() }}</div>
        </div>
    </div>

</x-secretaria-layout>
