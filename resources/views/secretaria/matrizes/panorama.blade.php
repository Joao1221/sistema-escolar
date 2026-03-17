<x-secretaria-layout>
    <div class="mb-8 flex justify-between items-center px-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Panorama Curricular da Rede</h1>
            <p class="text-sm text-gray-500 mt-1">Acompanhamento e conformidade das grades curriculares em tempo real.</p>
        </div>
        <div class="flex space-x-3">
             <a href="{{ route('secretaria.matrizes.index') }}" class="px-4 py-2 bg-white border border-gray-100 rounded-xl font-bold text-xs text-gray-400 uppercase tracking-widest hover:text-indigo-600 transition shadow-sm">
                Voltar
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H5" />
                </svg>
                <span>Imprimir</span>
            </button>
        </div>
    </div>

    {{-- Cards de Resumo --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total de Matrizes</p>
                    <p class="text-2xl font-black text-gray-800">{{ $totalMatrizes }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="bg-emerald-50 p-3 rounded-xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Cobertura da Rede</p>
                    <p class="text-2xl font-black text-gray-800">{{ $cobertura }}%</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-4 text-rose-600">
                <div class="bg-rose-50 p-3 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Turmas s/ Matriz</p>
                    <p class="text-2xl font-black">{{ $turmasSemMatriz->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-8 px-6 pb-12">
        {{-- Matrizes em Uso --}}
        @foreach ($turmasComMatriz as $matrizId => $turmas)
            @php $matriz = $turmas->first()->matriz; @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 bg-indigo-50/50 border-b border-indigo-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center space-x-2">
                             <span class="text-[10px] font-black bg-indigo-600 text-white px-2 py-0.5 rounded uppercase tracking-tighter">Matriz</span>
                             <h2 class="text-lg font-black text-indigo-900 uppercase tracking-tight">{{ $matriz->nome }}</h2>
                        </div>
                        <p class="text-xs text-indigo-600 font-bold uppercase mt-1">
                            {{ $matriz->modalidade->nome }} | {{ $matriz->serie_etapa ?: 'Multisseriada' }} | Ano: {{ $matriz->ano_vigencia }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Utilizada em</p>
                        <p class="text-xl font-black text-indigo-600">{{ $turmas->count() }} Turmas</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[10px] text-gray-400 uppercase bg-gray-50/50 tracking-widest font-bold">
                            <tr>
                                <th class="py-4 px-6">Unidade Escolar</th>
                                <th class="py-4 px-6 text-center">Turma</th>
                                <th class="py-4 px-6 text-center">Turno</th>
                                <th class="py-4 px-6 text-center">Alunos (Est.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 italic">
                            @foreach ($turmas as $turma)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 font-bold text-gray-700 uppercase">{{ $turma->escola->nome }}</td>
                                    <td class="py-4 px-6 text-center font-black text-gray-600 uppercase">{{ $turma->nome }}</td>
                                    <td class="py-4 px-6 text-center text-xs text-gray-500 uppercase">{{ $turma->turno }}</td>
                                    <td class="py-4 px-6 text-center font-mono text-indigo-400 text-xs">-</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        {{-- Turmas Pendentes --}}
        @if ($turmasSemMatriz->count() > 0)
            <div class="bg-rose-50 rounded-2xl shadow-sm border border-rose-100 overflow-hidden mt-12">
                <div class="p-5 bg-rose-100/50 border-b border-rose-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-rose-900 uppercase tracking-tight flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Pendências: Turmas sem Matriz Vinculada
                        </h2>
                        <p class="text-xs text-rose-600 font-bold uppercase mt-1">Atenção: Estas turmas podem estar operando sem grade disciplinar oficial.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] text-rose-400 uppercase bg-rose-50 tracking-widest font-bold">
                            <tr>
                                <th class="py-4 px-6">Unidade Escolar</th>
                                <th class="py-4 px-6 text-center">Turma</th>
                                <th class="py-4 px-6 text-center">Modalidade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-100">
                            @foreach ($turmasSemMatriz as $turma)
                                <tr>
                                    <td class="py-4 px-6 font-bold text-rose-800 uppercase">{{ $turma->escola->nome }}</td>
                                    <td class="py-4 px-6 text-center font-black text-rose-700 uppercase">{{ $turma->nome }}</td>
                                    <td class="py-4 px-6 text-center text-[10px] font-bold text-rose-500 uppercase">{{ $turma->modalidade->nome }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-secretaria-layout>
