<x-secretaria-escolar-layout>

    <div class="flex justify-between items-start mb-6 px-6">
        <div class="flex items-center space-x-4">
            <div class="bg-emerald-100 p-4 rounded-2xl text-emerald-600">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 uppercase">Matrícula: {{ $matricula->tipo }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-xs font-mono font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">ANO: {{ $matricula->ano_letivo }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold 
                        @if ($matricula->status == 'ativa') bg-green-100 text-green-800 
                        @elseif ($matricula->status == 'cancelada') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ strtoupper($matricula->status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('secretaria-escolar.matriculas.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                Voltar
            </a>
            
            @if ($matricula->status == 'ativa')
                @if (!$matricula->turma_id)
                <a href="{{ route('secretaria-escolar.matriculas.enturmar.form', $matricula) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-emerald-700 transition">
                    Enturmar Aluno
                </a>
                @endif
                <a href="{{ route('secretaria-escolar.matriculas.transferir.form', $matricula) }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-orange-700 transition">
                    Transferir
                </a>
            @endif

            @if ($matricula->status == 'concluida')
            <a href="{{ route('secretaria-escolar.matriculas.rematricular.form', $matricula) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700 transition">
                Rematricular
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Informações do Aluno e Matrícula --}}
        <div class="col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Dados do Aluno</h3>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 uppercase">{{ $matricula->aluno->nome_completo }}</p>
                        <p class="text-[10px] text-emerald-600 font-mono italic">RGM: {{ $matricula->aluno->rgm }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Mãe / Responsável</p>
                        <p class="text-xs text-gray-800 uppercase">{{ $matricula->aluno->nome_mae }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Telefone</p>
                        <p class="text-xs text-gray-800">{{ $matricula->aluno->responsavel_telefone }}</p>
                    </div>
                </div>
                <a href="{{ route('secretaria-escolar.alunos.show', $matricula->aluno) }}" class="mt-4 block text-center text-[10px] font-bold text-emerald-600 uppercase hover:underline">Ver ficha completa do aluno</a>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Detalhes da Matrícula</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Unidade Escolar</p>
                        <p class="text-sm text-gray-800 font-bold uppercase">{{ $matricula->escola->nome }}</p>
                    </div>
                    @if ($matricula->turma)
                    <div>
                        <p class="text-[10px] text-emerald-500 font-bold uppercase">Turma Atual</p>
                        <p class="text-sm text-emerald-700 font-bold uppercase">{{ $matricula->turma->nome }} ({{ $matricula->turma->turno }})</p>
                    </div>
                    @else
                    <div class="bg-yellow-50 p-2 rounded border border-yellow-100">
                        <p class="text-[10px] text-yellow-600 font-bold uppercase">Atenção</p>
                        <p class="text-[10px] text-yellow-700">Aluno aguardando alocação em turma.</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Ingresso em</p>
                        <p class="text-xs text-gray-800">{{ $matricula->data_matricula->format('d/m/Y') }}</p>
                    </div>
                    @if ($matricula->data_encerramento)
                    <div>
                        <p class="text-[10px] text-red-400 font-bold uppercase">Encerrada em</p>
                        <p class="text-xs text-red-800 font-bold">{{ $matricula->data_encerramento->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Histórico de Movimentações --}}
        <div class="col-span-1 md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-widest italic">Histórico de Movimentação</h3>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="p-6">
                    <div class="relative">
                        {{-- Linha vertical central --}}
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-100"></div>

                        <div class="space-y-8">
                            @foreach ($matricula->historico as $item)
                            <div class="relative pl-10">
                                {{-- Ponto do histórico --}}
                                <div class="absolute left-[13px] top-1 w-2.5 h-2.5 rounded-full border-2 
                                    {{ $item->acao == 'criacao' ? 'bg-green-500 border-green-200' : 'bg-emerald-400 border-emerald-100' }}"></div>
                                
                                <div class="flex flex-col">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest bg-emerald-50 px-2 py-0.5 rounded">{{ str_replace('_', ' ', $item->acao) }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $item->descricao }}</p>
                                    <div class="mt-2 flex items-center space-x-1">
                                        <span class="text-[9px] text-gray-400 uppercase font-bold">Responsável:</span>
                                        <span class="text-[9px] text-gray-500 italic">{{ $item->usuario->name ?? 'Sistema' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @if ($matricula->observacoes)
            <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
                <h4 class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest mb-2">Observações Internas</h4>
                <p class="text-sm text-emerald-900 italic">"{{ $matricula->observacoes }}"</p>
            </div>
            @endif
        </div>
    </div>

</x-secretaria-escolar-layout>
