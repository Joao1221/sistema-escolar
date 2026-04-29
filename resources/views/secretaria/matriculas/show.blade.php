<x-secretaria-layout>

    @include('partials.print-header', [
        'tituloPrint' => 'Ficha de matricula',
        'subtituloPrint' => 'Aluno: ' . $matricula->aluno->nome_completo . ' | RGM: ' . $matricula->aluno->rgm . ' | Ano letivo: ' . $matricula->ano_letivo,
        'escolaPrint' => $matricula->escola,
    ])

    <div class="no-print flex justify-between items-start mb-6">
        <div class="flex items-center space-x-4">
            <div class="bg-indigo-100 p-4 rounded-2xl text-indigo-600">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 uppercase">Matrícula: {{ $matricula->aluno->nome_completo }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">RGM: {{ $matricula->aluno->rgm }}</span>
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
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-900 rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 transition">
                Imprimir
            </button>
            <a href="{{ route('secretaria.matriculas.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                Voltar
            </a>
            <div class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg font-semibold text-xs uppercase tracking-widest cursor-not-allowed italic">
                Conselho Municipal (Leitura)
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 print:grid-cols-1">
        
        <div class="col-span-1 space-y-6">
            <div class="print-break-avoid print-readable bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Vínculo Institucional</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Escola Responsável</p>
                        <p class="text-sm text-gray-800 font-bold uppercase">{{ $matricula->escola->nome }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Turma / Turno</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $matricula->turma ? $matricula->turma->nome : 'NÃO ALOCADO' }} ({{ $matricula->turma->turno ?? '-' }})</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Tipo de Ingresso</p>
                        <p class="text-xs text-gray-800 font-bold uppercase">{{ $matricula->tipo }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Período Letivo</p>
                        <p class="text-xs text-gray-800 uppercase">{{ $matricula->ano_letivo }}</p>
                    </div>
                </div>
            </div>

            <div class="print-break-avoid print-readable bg-indigo-900 text-white p-6 rounded-2xl shadow-sm">
                <h4 class="text-indigo-300 text-[10px] font-bold uppercase tracking-widest mb-4">Dados do Aluno</h4>
                <div class="space-y-3">
                    <p class="text-sm font-medium uppercase">{{ $matricula->aluno->nome_completo }}</p>
                    <p class="text-xs text-indigo-200">CPF: {{ $matricula->aluno->cpf ?: 'Não informado' }}</p>
                    <p class="text-xs text-indigo-200 uppercase">Mãe: {{ $matricula->aluno->nome_mae }}</p>
                </div>
            </div>
        </div>

        <div class="col-span-1 md:col-span-2 space-y-6">
            <div class="print-break-avoid print-readable bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-widest italic">Histórico de Eventos</h3>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                        <div class="space-y-8">
                            @foreach ($matricula->historico as $item)
                            <div class="print-break-avoid relative pl-10">
                                <div class="absolute left-[13px] top-1 w-2.5 h-2.5 rounded-full border-2 bg-indigo-500 border-indigo-100"></div>
                                <div class="flex flex-col">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded">{{ str_replace('_', ' ', $item->acao) }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $item->descricao }}</p>
                                    <p class="text-[9px] text-gray-400 mt-1 italic">Operador: {{ $item->usuario->name ?? 'Sistema' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-secretaria-layout>
