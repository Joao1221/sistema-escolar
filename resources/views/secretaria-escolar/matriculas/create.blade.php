<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 uppercase italic tracking-tight">Nova Matrícula</h1>
            <p class="text-slate-500 mt-1 uppercase decoration-emerald-200 decoration-2">Inicie o vínculo do aluno com a unidade escolar</p>
        </div>
        <a href="{{ route('secretaria-escolar.matriculas.index') }}" class="flex items-center space-x-2 text-sm text-slate-400 hover:text-emerald-600 font-bold uppercase transition group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Voltar para Listagem</span>
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <form method="POST" action="{{ route('secretaria-escolar.matriculas.store') }}" x-data="{ tipo: 'regular' }">
            @csrf
            
            <div class="p-6 space-y-8">
                
                {{-- Seção: Tipo de Matrícula --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-full">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-4">Selecione o Tipo de Matrícula</label>
                        <div class="flex space-x-4">
                            <label class="flex-1 cursor-pointer group">
                                <input type="radio" name="tipo" value="regular" x-model="tipo" class="hidden peer" checked>
                                <div class="p-4 border-2 rounded-xl transition-all peer-checked:border-emerald-600 peer-checked:bg-emerald-50 group-hover:bg-gray-50 text-center">
                                    <div class="text-xs font-bold uppercase text-gray-700 peer-checked:text-emerald-700">📚 Ensino Regular</div>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase">Educação Básica Comum</p>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer group">
                                <input type="radio" name="tipo" value="aee" x-model="tipo" class="hidden peer">
                                <div class="p-4 border-2 rounded-xl transition-all peer-checked:border-emerald-600 peer-checked:bg-emerald-50 group-hover:bg-gray-50 text-center">
                                    <div class="text-xs font-bold uppercase text-gray-700 peer-checked:text-emerald-700">🎨 AEE</div>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase">Atendimento Especializado</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Aluno --}}
                    <div class="col-span-full">
                        <x-input-label for="aluno_id" :value="__('Aluno')" />
                        <select id="aluno_id" name="aluno_id" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm font-medium uppercase text-sm" required x-on:change="
                            $dispatch('aluno-selecionado', $el.value)
                        ">
                            <option value="">Selecione o aluno...</option>
                            @foreach ($alunos as $aluno)
                                <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                                    {{ $aluno->rgm }} - {{ $aluno->nome_completo }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('aluno_id')" class="mt-2" />
                    </div>

                    {{-- Ano Letivo --}}
                    <div>
                        <x-input-label for="ano_letivo" :value="__('Ano Letivo')" />
                        <x-text-input id="ano_letivo" name="ano_letivo" type="number" class="mt-1 block w-full" :value="old('ano_letivo', date('Y'))" required />
                        <x-input-error :messages="$errors->get('ano_letivo')" class="mt-2" />
                    </div>

                    {{-- Data Matrícula --}}
                    <div>
                        <x-input-label for="data_matricula" :value="__('Data da Matrícula')" />
                        <x-text-input id="data_matricula" name="data_matricula" type="date" class="mt-1 block w-full" :value="old('data_matricula', date('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('data_matricula')" class="mt-2" />
                    </div>

                    {{-- Seleção de Turma (Opcional no momento da matrícula) --}}
                    <div x-show="tipo === 'regular'" class="col-span-full">
                        <x-input-label for="turma_id" :value="__('Vincular à Turma (Opcional)')" />
                        <select id="turma_id" name="turma_id" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm font-medium uppercase text-sm">
                            <option value="">Decidir depois (Enturmar posteriormente)</option>
                            @foreach ($turmas as $turma)
                                <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                                    [{{ $turma->turno }}] {{ $turma->nome }} - {{ $turma->ano_letivo }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-tighter">O aluno pode ser matriculado sem turma e alocado depois.</p>
                    </div>

                    {{-- Vinculo com Regular se for AEE (Matrícula Dupla) --}}
                    <div x-show="tipo === 'aee'" class="col-span-full" style="display: none;">
                        <div class="bg-purple-50 p-6 rounded-2xl border border-purple-100">
                            <x-input-label for="matricula_regular_id" :value="__('Vincular a uma Matrícula Regular? (Matrícula Dupla)')" class="text-purple-700" />
                            <select id="matricula_regular_id" name="matricula_regular_id" class="mt-1 block w-full border-purple-200 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm font-medium uppercase text-sm">
                                <option value="">Não vincular (AEE isolado)</option>
                                {{-- TODO: Carregar via AJAX as matrículas regulares ativas do aluno selecionado --}}
                            </select>
                            <p class="text-[10px] text-purple-400 mt-2 uppercase">Utilize esta opção se o aluno já possuir matrícula regular nesta rede.</p>
                        </div>
                    </div>

                    {{-- Observações --}}
                    <div class="col-span-full">
                        <x-input-label for="observacoes" :value="__('Observações de Matrícula')" />
                        <textarea id="observacoes" name="observacoes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm" placeholder="Informações relevantes sobre este ingresso..."></textarea>
                    </div>

                </div>

            </div>

            <div class="flex items-center justify-end border-t pt-8 pb-10 px-6 space-x-6">
                <a href="{{ route('secretaria-escolar.matriculas.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-500 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <x-primary-button class="py-3 px-8 text-sm" style="background-color: #059669;">{{ __('Efetivar Matrícula') }}</x-primary-button>
            </div>
        </form>
    </div>

</x-secretaria-escolar-layout>
