<x-secretaria-escolar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                    {{ $matriz->nome }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 uppercase tracking-wider italic">Vigência: {{ $matriz->ano_vigencia }} | {{ $matriz->modalidade->nome }}</p>
            </div>
            <a href="{{ route('secretaria-escolar.curriculo.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Card Lateral de Info --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-6">
                <h3 class="text-lg font-bold text-emerald-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Ficha Técnica
                </h3>
                
                <div class="space-y-5">
                    <div class="flex justify-between items-start pb-4 border-b border-emerald-50">
                        <span class="text-xs font-bold text-emerald-700 uppercase">Modalidade</span>
                        <span class="text-sm font-semibold text-gray-800 text-right">{{ $matriz->modalidade->nome }}</span>
                    </div>
                    <div class="flex justify-between items-start pb-4 border-b border-emerald-50">
                        <span class="text-xs font-bold text-emerald-700 uppercase">Série / Etapa</span>
                        <span class="text-sm font-bold text-indigo-600 text-right uppercase">{{ $matriz->serie_etapa }}</span>
                    </div>
                    <div class="flex justify-between items-start pb-4 border-b border-emerald-50">
                        <span class="text-xs font-bold text-emerald-700 uppercase">Carga Total</span>
                        <span class="text-sm font-extrabold text-emerald-600 text-right">{{ $matriz->disciplinas->sum('pivot.carga_horaria') }} horas</span>
                    </div>
                    <div class="flex justify-between items-start pb-4 border-b border-emerald-50">
                        <span class="text-xs font-bold text-emerald-700 uppercase">Abrangência</span>
                        <span class="text-[10px] font-bold text-gray-500 text-right uppercase">
                            @if ($matriz->escola_id)
                                Específica: {{ $matriz->escola->nome }}
                            @else
                                Padrão Rede Municipal
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-emerald-600 rounded-2xl shadow-lg p-6 text-white overflow-hidden relative group">
                <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-emerald-500/30 transform group-hover:scale-110 transition duration-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm3 2h2v2H9V4zm4 2V4h1v2h-1zm-10 2v8h12V8H3z" clip-rule="evenodd" />
                </svg>
                <h4 class="text-sm font-bold uppercase tracking-widest opacity-80 mb-2">Relatório Operacional</h4>
                <p class="text-lg font-bold relative z-10 leading-tight">Esta grade deve ser seguida integralmente no ano de {{ $matriz->ano_vigencia }}.</p>
            </div>
        </div>

        {{-- Tabela de Disciplinas --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden self-start">
            <div class="px-6 py-5 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 tracking-tight underline decoration-emerald-500 decoration-2 underline-offset-4">Grade de Componentes Curriculares</h3>
                <span class="text-[10px] bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full font-bold uppercase">{{ $matriz->disciplinas->count() }} Disciplinas</span>
            </div>
            
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-400 uppercase bg-gray-50 tracking-wider">
                    <tr>
                        <th class="py-4 px-6">Componente</th>
                        <th class="py-4 px-6 text-center">Carga Horária</th>
                        <th class="py-4 px-6 text-center">Tipo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($matriz->disciplinas as $disciplina)
                    <tr class="hover:bg-emerald-50/20 transition-colors">
                        <td class="py-5 px-6">
                            <div class="font-bold text-slate-700 uppercase tracking-tight">{{ $disciplina->nome }}</div>
                            <div class="text-[10px] text-gray-400 font-mono mt-0.5">Cód: {{ $disciplina->codigo ?? 'N/A' }}</div>
                        </td>
                        <td class="py-5 px-6 text-center font-mono font-bold text-emerald-600 italic">
                            {{ $disciplina->pivot->carga_horaria }}h
                        </td>
                        <td class="py-5 px-6 text-center">
                            @if ($disciplina->pivot->obrigatoria)
                                <span class="text-[9px] bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full font-black uppercase border border-rose-100 tracking-tighter shadow-sm">Obrigatória</span>
                            @else
                                <span class="text-[9px] bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full font-black uppercase border border-slate-200 tracking-tighter">Optativa</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-emerald-50/30">
                    <tr>
                        <td class="py-4 px-6 font-bold text-emerald-900 uppercase text-xs">Total da Matriz</td>
                        <td class="py-4 px-6 text-center font-black text-emerald-700 text-base">
                            {{ $matriz->disciplinas->sum('pivot.carga_horaria') }}h
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-secretaria-escolar-layout>
