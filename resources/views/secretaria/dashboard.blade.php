<x-secretaria-layout>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Painel da Secretaria de Educação</h1>
        <p class="text-sm text-gray-500 mt-1">Visão geral da rede municipal de ensino</p>
    </div>

    {{-- Cards de Estatísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Escolas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-start space-x-4 hover:shadow-md transition-shadow">
            <div class="bg-indigo-100 text-indigo-600 p-3 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Escolas</p>
                <p class="text-3xl font-extrabold text-gray-800 leading-tight">{{ $totalEscolas }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    <span class="text-green-600 font-semibold">{{ $escolasAtivas }} ativas</span>
                    · {{ $escolasInativas }} inativas
                </p>
            </div>
        </div>

        {{-- Funcionários --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-start space-x-4 hover:shadow-md transition-shadow">
            <div class="bg-teal-100 text-teal-600 p-3 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Funcionários</p>
                <p class="text-3xl font-extrabold text-gray-800 leading-tight">{{ $totalFuncionarios }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    <span class="text-green-600 font-semibold">{{ $funcionariosAtivos }} ativos</span>
                </p>
            </div>
        </div>

        {{-- Usuários --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-start space-x-4 hover:shadow-md transition-shadow">
            <div class="bg-violet-100 text-violet-600 p-3 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Usuários</p>
                <p class="text-3xl font-extrabold text-gray-800 leading-tight">{{ $totalUsuarios }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    <span class="text-green-600 font-semibold">{{ $usuariosAtivos }} ativos</span>
                    · {{ $totalUsuarios - $usuariosAtivos }} inativos
                </p>
            </div>
        </div>

        {{-- Acesso Rápido --}}
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-indigo-200 text-sm font-medium mb-1">Acesso Rápido</p>
                <p class="text-white font-bold text-base leading-tight">Configurações da Secretaria</p>
            </div>
            <div class="mt-4 space-y-2">
                @can('editar instituicao')
                <a href="{{ route('secretaria.instituicao.edit') }}" class="block text-xs text-indigo-200 hover:text-white transition">
                    → Editar Dados Institucionais
                </a>
                @endcan
                @can('visualizar configuracoes')
                <a href="{{ route('secretaria.configuracoes.index') }}" class="block text-xs text-indigo-200 hover:text-white transition">
                    → Parâmetros Globais
                </a>
                @endcan
            </div>
        </div>

    </div>

    {{-- Linha inferior: últimas escolas + ações rápidas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Últimas Escolas Cadastradas --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-700 text-base">Últimas Escolas Cadastradas</h2>
                @can('visualizar escolas')
                <a href="{{ route('secretaria.escolas.index') }}" class="text-sm text-indigo-600 hover:underline font-medium">Ver todas</a>
                @endcan
            </div>
            @if ($ultimasEscolas->isEmpty())
                <p class="text-gray-400 text-sm">Nenhuma escola cadastrada ainda.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="pb-3">Escola</th>
                            <th class="pb-3 text-center">Status</th>
                            <th class="pb-3 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($ultimasEscolas as $escola)
                        <tr>
                            <td class="py-3 pr-4">
                                <div class="font-semibold text-gray-800">{{ $escola->nome }}</div>
                                <div class="text-xs text-gray-400">{{ $escola->cidade }}/{{ $escola->uf }}</div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $escola->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $escola->ativo ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('secretaria.escolas.show', $escola) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Atalhos do Módulo --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-700 text-base mb-4">Atalhos</h2>
            <div class="space-y-3">

                @can('criar escola')
                <a href="{{ route('secretaria.escolas.create') }}" class="flex items-center space-x-3 p-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 transition group">
                    <div class="bg-indigo-200 text-indigo-700 p-2 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-indigo-700 group-hover:text-indigo-900">Nova Escola</span>
                </a>
                @endcan

                @can('criar funcionario')
                <a href="{{ route('secretaria.funcionarios.create') }}" class="flex items-center space-x-3 p-3 rounded-xl bg-teal-50 hover:bg-teal-100 transition group">
                    <div class="bg-teal-200 text-teal-700 p-2 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-teal-700 group-hover:text-teal-900">Novo Funcionário</span>
                </a>
                @endcan

                @can('criar usuario')
                <a href="{{ route('secretaria.usuarios.create') }}" class="flex items-center space-x-3 p-3 rounded-xl bg-violet-50 hover:bg-violet-100 transition group">
                    <div class="bg-violet-200 text-violet-700 p-2 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-violet-700 group-hover:text-violet-900">Novo Usuário</span>
                </a>
                @endcan

                @can('visualizar instituicao')
                <a href="{{ route('secretaria.instituicao.show') }}" class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="bg-gray-200 text-gray-700 p-2 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Dados Institucionais</span>
                </a>
                @endcan

            </div>
        </div>

    </div>

</x-secretaria-layout>
