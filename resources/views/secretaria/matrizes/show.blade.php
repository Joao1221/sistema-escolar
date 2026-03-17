<x-secretaria-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $matriz->nome }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 uppercase">Detalhes da Composição Curricular</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('secretaria.matrizes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition">
                    Voltar
                </a>
                @can('gerenciar matrizes')
                <a href="{{ route('secretaria.matrizes.edit', $matriz) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Editar Matriz
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card de Informações Gerais --}}
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 self-start">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informações Gerais
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Modalidade</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $matriz->modalidade->nome }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Série / Etapa</p>
                    <p class="text-sm font-semibold text-indigo-600 uppercase">{{ $matriz->serie_etapa }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Ano de Vigência</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $matriz->ano_vigencia }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Abrangência</p>
                    <p class="text-sm font-semibold text-gray-800">
                        @if ($matriz->escola_id)
                            Unidade: {{ $matriz->escola->nome }}
                        @else
                            Rede Pública Municipal (SUE)
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Carga Horária Total</p>
                    <p class="text-lg font-bold text-emerald-600">{{ $matriz->disciplinas->sum('pivot.carga_horaria') }}h</p>
                </div>
            </div>
        </div>

        {{-- Lista de Disciplinas --}}
        <div class="md:col-span-2 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="bg-gray-50/50 p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Composição de Disciplinas</h3>
            </div>
            
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-400 uppercase bg-gray-50 tracking-wider">
                    <tr>
                        <th class="py-3 px-6">Disciplina</th>
                        <th class="py-3 px-6 text-center">Carga (h)</th>
                        <th class="py-3 px-6 text-center">Obrigatoriedade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($matriz->disciplinas as $disciplina)
                    <tr>
                        <td class="py-4 px-6 font-bold text-gray-800 uppercase">{{ $disciplina->nome }}</td>
                        <td class="py-4 px-6 text-center italic font-medium">{{ $disciplina->pivot->carga_horaria }}h</td>
                        <td class="py-4 px-6 text-center">
                            @if ($disciplina->pivot->obrigatoria)
                                <span class="text-[10px] bg-red-50 text-red-600 px-2 py-1 rounded font-bold uppercase border border-red-100">Obrigatória</span>
                            @else
                                <span class="text-[10px] bg-gray-50 text-gray-500 px-2 py-1 rounded font-bold uppercase border border-gray-100">Opcional</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-secretaria-layout>
