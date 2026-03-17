<x-secretaria-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center relative z-10">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Funcionários da Rede') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Servidores e colaboradores da rede municipal de ensino</p>
            </div>
            @can('criar funcionario')
            <a href="{{ route('secretaria.funcionarios.create') }}" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                + Novo Funcionário
            </a>
            @endcan
        </div>
    </x-slot>

    {{-- Filtros --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('secretaria.funcionarios.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <x-input-label for="nome" :value="__('Nome')" />
                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="request('nome')" placeholder="Digite o nome..." />
            </div>
            <div>
                <x-input-label for="cargo" :value="__('Cargo')" />
                <select id="cargo" name="cargo" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    @foreach (['Professor', 'Diretor', 'Coordenador', 'Secretário Escolar', 'Nutricionista'] as $c)
                        <option value="{{ $c }}" {{ request('cargo') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="escola_id" :value="__('Escola')" />
                <select id="escola_id" name="escola_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todas as Escolas</option>
                    @foreach ($escolas as $escola)
                        <option value="{{ $escola->id }}" {{ request('escola_id') == $escola->id ? 'selected' : '' }}>{{ $escola->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <x-primary-button type="submit">Filtrar</x-primary-button>
                <a href="{{ route('secretaria.funcionarios.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-xs font-bold uppercase tracking-widest">Limpar</a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6 text-gray-900 text-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Funcionário</th>
                            <th class="py-3 px-6">CPF</th>
                            <th class="py-3 px-6 text-center">Cargo</th>
                            <th class="py-3 px-6">Escolas</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($funcionarios as $func)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800">{{ $func->nome }}</div>
                                <div class="text-xs text-gray-400">{{ $func->email }}</div>
                            </td>
                            <td class="py-4 px-6">{{ $func->cpf }}</td>
                            <td class="py-4 px-6 text-center italic">{{ $func->cargo }}</td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($func->escolas as $escola)
                                        <span class="bg-blue-50 text-blue-700 text-[10px] px-2 py-0.5 rounded border border-blue-100 uppercase">{{ $escola->nome }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $func->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $func->ativo ? 'ATIVO' : 'INATIVO' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('secretaria.funcionarios.show', $func) }}" class="text-blue-600 hover:text-blue-900 font-bold">Ver</a>
                                @can('editar funcionario')
                                <a href="{{ route('secretaria.funcionarios.edit', $func) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Editar</a>
                                @endcan
                                @can('ativar inativar funcionario')
                                <form action="{{ route('secretaria.funcionarios.toggle', $func) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $func->ativo ? 'text-red-500' : 'text-green-500' }} font-bold text-[10px] uppercase">
                                        {{ $func->ativo ? 'Inativar' : 'Ativar' }}
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Nenhum funcionário encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $funcionarios->links() }}</div>
        </div>
    </div>

</x-secretaria-layout>
