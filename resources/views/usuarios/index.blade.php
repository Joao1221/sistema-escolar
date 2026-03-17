<x-secretaria-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center relative z-10">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Gestão de Usuários') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Contas de acesso ao sistema</p>
            </div>
            @can('criar usuario')
            <a href="{{ route('secretaria.usuarios.create') }}" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                + Novo Usuário
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto relative">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Nome</th>
                            <th class="py-3 px-6">E-mail</th>
                            <th class="py-3 px-6">Perfil</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($usuarios as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="py-4 px-6">{{ $user->email }}</td>
                            <td class="py-4 px-6">
                                @foreach ($user->getRoleNames() as $role)
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">{{ $role }}</span>
                                @endforeach
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if ($user->ativo)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Ativo</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Inativo</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                @hasanyrole('Administrador da Rede')
                                <a href="{{ route('secretaria.usuarios.edit', $user->id) }}" class="font-medium text-indigo-600 hover:underline">Editar</a>
                                @if ($user->id !== Auth::id())
                                <form action="{{ route('secretaria.usuarios.status', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="font-medium hover:underline {{ $user->ativo ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $user->ativo ? 'Inativar' : 'Ativar' }}
                                    </button>
                                </form>
                                @endif
                                @endhasanyrole
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $usuarios->links() }}</div>
        </div>
    </div>

</x-secretaria-layout>
