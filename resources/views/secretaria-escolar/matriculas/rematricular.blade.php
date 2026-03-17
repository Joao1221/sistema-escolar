<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 uppercase italic text-indigo-600">Rematrícula</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase italic">Renovação de vínculo para novo ano letivo</p>
        </div>
        <a href="{{ route('secretaria-escolar.matriculas.show', $matricula) }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <form method="POST" action="{{ route('secretaria-escolar.matriculas.rematricular.store', $matricula) }}">
            @csrf
            
            <div class="p-6 space-y-6">
                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 flex items-center space-x-4 mb-6">
                    <div class="bg-indigo-600 p-2 rounded-lg text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest">Aluno em Rematrícula</p>
                        <p class="text-sm font-bold text-indigo-900 uppercase italic">{{ $matricula->aluno->nome_completo }}</p>
                    </div>
                </div>

                <div>
                    <x-input-label for="ano_letivo" :value="__('Próximo Ano Letivo')" />
                    <x-text-input id="ano_letivo" name="ano_letivo" type="number" class="mt-1 block w-full" :value="$matricula->ano_letivo + 1" required />
                    <x-input-error :messages="$errors->get('ano_letivo')" class="mt-2" />
                    <p class="text-[10px] text-gray-400 mt-2 uppercase italic leading-tight">A rematrícula encerrará o ciclo atual e criará uma nova matrícula ativa no ano selecionado.</p>
                </div>
            </div>

            <div class="flex items-center justify-end bg-gray-50/50 border-t pt-4 pb-4 px-6 space-x-4">
                <x-primary-button style="background-color: #4f46e5;">{{ __('Efetivar Rematrícula') }}</x-primary-button>
            </div>
        </form>
    </div>

</x-secretaria-escolar-layout>
