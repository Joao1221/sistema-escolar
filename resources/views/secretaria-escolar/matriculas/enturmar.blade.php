<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 uppercase italic">Enturmar Aluno</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase">Alocação de vaga e enturmação pedagógica</p>
        </div>
        <a href="{{ route('secretaria-escolar.matriculas.show', $matricula) }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <form method="POST" action="{{ route('secretaria-escolar.matriculas.enturmar.store', $matricula) }}">
            @csrf
            
            <div class="p-6 space-y-6">
                <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-center space-x-4 mb-6">
                    <div class="bg-emerald-600 p-2 rounded-lg text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-widest">Aluno Selecionado</p>
                        <p class="text-sm font-bold text-emerald-900 uppercase italic">{{ $matricula->aluno->nome_completo }}</p>
                    </div>
                </div>

                <div>
                    <x-input-label for="turma_id" :value="__('Selecione a Turma Destino')" />
                    <select id="turma_id" name="turma_id" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm font-medium uppercase text-sm" required>
                        <option value="">Selecione a turma...</option>
                        @foreach ($turmas as $turma)
                            <option value="{{ $turma->id }}">
                                [{{ $turma->turno }}] {{ $turma->nome }} - Vagas: {{ $turma->vagas }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('turma_id')" class="mt-2" />
                    <p class="text-[10px] text-gray-400 mt-2 uppercase italic">Apenas turmas ativas desta unidade são listadas.</p>
                </div>
            </div>

            <div class="flex items-center justify-end bg-gray-50/50 border-t pt-4 pb-4 px-6 space-x-4">
                <x-primary-button style="background-color: #059669;">{{ __('Efetivar Enturmação') }}</x-primary-button>
            </div>
        </form>
    </div>

</x-secretaria-escolar-layout>
