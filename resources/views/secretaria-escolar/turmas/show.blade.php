<x-secretaria-escolar-layout>

    <div class="flex justify-between items-start mb-6 px-6">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded uppercase">{{ $turma->ano_letivo }}</span>
                <h1 class="text-2xl font-bold text-gray-800 uppercase">{{ $turma->nome }}</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1 uppercase">{{ $turma->escola->nome }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('secretaria-escolar.turmas.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                Voltar
            </a>
            @can('editar turmas')
            <a href="{{ route('secretaria-escolar.turmas.edit', $turma) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-emerald-700 transition" style="background-color: #059669;">
                Editar Turma
            </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Card de Informações --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b pb-2">Dados da Turma</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Modalidade de Ensino</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->modalidade->nome }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Série / Etapa</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->serie_etapa }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Turno de Funcionamento</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->turno }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Capacidade (Vagas)</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->vagas }} vagas</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Multisseriada</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->is_multisseriada ? 'Sim' : 'Não' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Status Operacional</p>
                        <span class="inline-flex mt-1 items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $turma->ativa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $turma->ativa ? 'ATIVA' : 'INATIVA' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Área para futura expansão (Alunos da Turma) --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest border-b pb-2 flex-grow">Alunos Matriculados</h3>
                    <span class="ml-4 px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">0 Alunos</span>
                </div>
                <div class="py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="text-sm">O gerenciamento de matrículas será implementado em etapas futuras.</p>
                </div>
            </div>
        </div>

        {{-- Sidebar de Informações da Unidade --}}
        <div class="space-y-6">
            <div class="bg-emerald-900 text-white rounded-2xl p-6 shadow-sm">
                <h4 class="text-emerald-300 text-[10px] font-bold uppercase tracking-widest mb-4">Unidade Escolar</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-emerald-400 font-bold uppercase">Nome</p>
                        <p class="text-sm font-bold uppercase">{{ $turma->escola->nome }}</p>
                    </div>
                </div>
            </div>

            {{-- Matriz Curricular --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-emerald-100">
                <h4 class="text-emerald-800 text-[10px] font-bold uppercase tracking-widest mb-4 border-b border-emerald-50 pb-2">Base Curricular</h4>
                @if ($turma->matriz)
                    <div class="mb-4">
                        <p class="text-xs font-bold text-gray-800 uppercase">{{ $turma->matriz->nome }}</p>
                        <p class="text-[10px] text-emerald-600 font-medium">Vigência: {{ $turma->matriz->ano_vigencia }}</p>
                    </div>
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($turma->matriz->disciplinas as $disc)
                            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0 text-[11px]">
                                <span class="text-gray-600 font-medium uppercase">{{ $disc->nome }}</span>
                                <span class="text-emerald-700 font-bold italic">{{ $disc->pivot->carga_horaria }}h</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-3 border-t border-emerald-100 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-emerald-800 uppercase text-right">Carga Total</span>
                        <span class="text-xs font-extrabold text-emerald-700 italic text-right">{{ $turma->matriz->disciplinas->sum('pivot.carga_horaria') }}h</span>
                    </div>
                @else
                    <div class="py-6 text-center text-gray-400 bg-gray-50 rounded-xl">
                        <svg class="w-8 h-8 mx-auto mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-[10px] font-bold uppercase tracking-tighter">Matriz Não Associada</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-secretaria-escolar-layout>
