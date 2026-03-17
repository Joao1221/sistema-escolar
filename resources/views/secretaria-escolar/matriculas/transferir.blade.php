<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 uppercase italic text-orange-600">Registrar Transferência</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase italic">Saída do aluno da unidade escolar</p>
        </div>
        <a href="{{ route('secretaria-escolar.matriculas.show', $matricula) }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <form method="POST" action="{{ route('secretaria-escolar.matriculas.transferir.store', $matricula) }}">
            @csrf
            
            <div class="p-6 space-y-6">
                <div class="bg-orange-50 p-4 rounded-xl border border-orange-100 flex items-center space-x-4 mb-6">
                    <div class="bg-orange-600 p-2 rounded-lg text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-orange-600 font-bold uppercase tracking-widest">Aluno em Transferência</p>
                        <p class="text-sm font-bold text-orange-900 uppercase italic">{{ $matricula->aluno->nome_completo }}</p>
                    </div>
                </div>

                <div>
                    <x-input-label for="motivo" :value="__('Motivo da Transferência / Destino')" />
                    <x-text-input id="motivo" name="motivo" type="text" class="mt-1 block w-full" placeholder="Ex: Mudança de cidade / Transferido para Escola X" required />
                    <x-input-error :messages="$errors->get('motivo')" class="mt-2" />
                    <p class="text-[10px] text-gray-400 mt-2 mb-4 uppercase italic">Esta ação irá encerrar a matrícula atual do aluno nesta escola.</p>
                </div>

                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-xs text-red-800 font-bold uppercase italic">Atenção: Esta ação é irreversível.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end bg-gray-50/50 border-t pt-4 pb-4 px-6 space-x-4">
                <x-primary-button style="background-color: #ea580c;">{{ __('Confirmar Transferência') }}</x-primary-button>
            </div>
        </form>
    </div>

</x-secretaria-escolar-layout>
