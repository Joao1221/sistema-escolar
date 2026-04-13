<x-secretaria-escolar-layout>
    <div class="mb-8 px-4 lg:px-0">
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-slate-900 font-outfit tracking-tight">Painel Operacional</h1>
        <p class="text-slate-500 mt-1 text-sm lg:text-lg">Visão consolidada da unidade escolar e fluxo de matrículas.</p>
    </div>

    {{-- Grid de Estatísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8 mb-8 px-4 lg:px-0">
        
        {{-- Total de Alunos --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center space-x-4 mb-6">
                <div class="p-3 bg-blue-600 rounded-2xl text-white shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest">Base de Alunos</h3>
            </div>
            <p class="text-4xl font-bold text-slate-900 font-outfit">{{ $stats['total_alunos'] }}</p>
            <p class="text-xs text-slate-500 mt-2 font-medium">Cadastrados no sistema</p>
        </div>

        {{-- Matrículas Ativas --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center space-x-4 mb-6">
                <div class="p-3 bg-emerald-600 rounded-2xl text-white shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest">Matrículas Ativas</h3>
            </div>
            <p class="text-4xl font-bold text-slate-900 font-outfit">{{ $stats['matriculas_ativas'] }}</p>
            <div class="flex items-center space-x-2 mt-2">
                <span class="text-[10px] font-bold text-emerald-600 uppercase bg-emerald-50 px-1.5 py-0.5 rounded">{{ $stats['matriculas_regular'] }} Regular</span>
                <span class="text-[10px] font-bold text-purple-600 uppercase bg-purple-50 px-1.5 py-0.5 rounded">{{ $stats['matriculas_aee'] }} AEE</span>
            </div>
        </div>

        {{-- Turmas Ativas --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center space-x-4 mb-6">
                <div class="p-3 bg-indigo-600 rounded-2xl text-white shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest">Turmas em Funcionamento</h3>
            </div>
            <p class="text-4xl font-bold text-slate-900 font-outfit">{{ $stats['turmas_ativas'] }}</p>
            <p class="text-xs text-slate-500 mt-2 font-medium">Unidades didáticas ativas</p>
        </div>

        {{-- Ocupação --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center space-x-4 mb-6">
                <div class="p-3 bg-orange-600 rounded-2xl text-white shadow-lg shadow-orange-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest">Taxa de Ocupação</h3>
            </div>
            @php
                $porcentagem = $stats['ocupacao_vagas']['total'] > 0 ? round(($stats['ocupacao_vagas']['ocupadas'] / $stats['ocupacao_vagas']['total']) * 100) : 0;
            @endphp
            <div class="flex items-end justify-between">
                <p class="text-4xl font-bold text-slate-900 font-outfit">{{ $porcentagem }}%</p>
                <p class="text-[10px] text-slate-500 mb-1 font-bold uppercase">{{ $stats['ocupacao_vagas']['ocupadas'] }}/{{ $stats['ocupacao_vagas']['total'] }} Vagas</p>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-orange-500 h-full transition-all duration-1000" style="width: {{ $porcentagem }}%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 px-4 lg:px-0 pb-10">
        <!-- Recent Activities Feed -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-900 font-outfit">Atividade Recente</h2>
                    <a href="{{ route('secretaria-escolar.matriculas.index') }}" class="text-xs font-bold text-emerald-600 uppercase tracking-widest hover:underline">Ver tudo</a>
                </div>
                <div class="p-8">
                    <div class="space-y-8">
                        @forelse ($recent_activities as $activity)
                        <div class="flex items-start space-x-5 group">
                            <div class="mt-1">
                                <span class="flex w-3 h-3 rounded-full {{ $activity->acao == 'criacao' ? 'bg-green-500 ring-4 ring-green-100' : 'bg-emerald-400 ring-4 ring-emerald-50' }}"></span>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <p class="text-sm font-bold text-slate-800 uppercase tracking-tight group-hover:text-emerald-700 transition">
                                        {{ $activity->matricula->aluno->nome_completo ?? 'Aluno não identificado' }}
                                    </p>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $activity->descricao }}</p>
                                <div class="mt-2 flex items-center space-x-2">
                                    <span class="text-[9px] text-emerald-600 font-bold uppercase bg-emerald-50 px-2 py-0.5 rounded tracking-widest">{{ str_replace('_', ' ', $activity->acao) }}</span>
                                    <span class="text-[9px] text-slate-300">por {{ $activity->usuario->name ?? 'Sistema' }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center">
                            <p class="text-slate-400 italic text-sm">Nenhuma atividade registrada hoje.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Widgets -->
        <div class="space-y-8">
            <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-emerald-900/20">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500 rounded-full opacity-10"></div>
                <h3 class="text-lg font-bold font-outfit mb-6">Ações Rápidas</h3>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('secretaria-escolar.dados-escola.edit') }}" class="flex items-center p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl hover:bg-emerald-500/20 transition group">
                        <div class="bg-emerald-500 p-2 rounded-xl mr-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest text-emerald-100">Dados da Escola</span>
                    </a>
                    <a href="{{ route('secretaria-escolar.matriculas.create') }}" class="flex items-center p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition group">
                        <div class="bg-white/10 p-2 rounded-xl mr-4 group-hover:scale-110 transition-transform text-white">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest text-white">Nova Matrícula</span>
                    </a>
                    <a href="{{ route('secretaria-escolar.alunos.create') }}" class="flex items-center p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition group">
                        <div class="bg-white/10 p-2 rounded-xl mr-4 group-hover:scale-110 transition-transform text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest">Cadastrar Aluno</span>
                    </a>
                    <a href="{{ route('secretaria-escolar.turmas.create') }}" class="flex items-center p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition group">
                        <div class="bg-white/10 p-2 rounded-xl mr-4 group-hover:scale-110 transition-transform text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest">Nova Turma</span>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                <h3 class="text-slate-900 font-bold font-outfit mb-6">Módulos em Breve</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 opacity-40">
                        <div class="p-3 bg-slate-50 rounded-2xl">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Censo Escolar 2026</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Exportação de dados</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 opacity-40">
                        <div class="p-3 bg-slate-50 rounded-2xl">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Frequência Diária</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Diário Eletrônico</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-secretaria-escolar-layout>
