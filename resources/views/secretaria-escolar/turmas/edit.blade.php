<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-tight">Editar Turma: {{ $turma->nome }}</h1>
            <p class="text-slate-500 mt-1 uppercase">Série: {{ $turma->serie_etapa }} | Ano Letivo: {{ $turma->ano_letivo }}</p>
        </div>
        <a href="{{ route('secretaria-escolar.turmas.index') }}" class="flex items-center space-x-2 text-sm text-slate-400 hover:text-emerald-600 font-bold uppercase transition group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Voltar para Listagem</span>
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <form method="POST" action="{{ route('secretaria-escolar.turmas.update', $turma) }}" class="p-6 space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nome da Turma --}}
                    <div>
                        <x-input-label for="nome" :value="__('Nome da Turma')" />
                        <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full uppercase" :value="old('nome', $turma->nome)" required />
                        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                    </div>

                    {{-- Ano Letivo --}}
                    <div>
                        <x-input-label for="ano_letivo" :value="__('Ano Letivo')" />
                        <x-text-input id="ano_letivo" name="ano_letivo" type="number" class="mt-1 block w-full" :value="old('ano_letivo', $turma->ano_letivo)" required />
                        <x-input-error :messages="$errors->get('ano_letivo')" class="mt-2" />
                    </div>

                    {{-- Matriz Curricular --}}
                    <div>
                        <x-input-label for="matriz_id" :value="__('Matriz Curricular')" />
                        <select id="matriz_id" name="matriz_id" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option value="">Selecione a Matriz (Opcional)</option>
                            @foreach ($matrizes as $mat)
                                <option value="{{ $mat->id }}" {{ old('matriz_id', $turma->matriz_id) == $mat->id ? 'selected' : '' }}>{{ $mat->nome }} ({{ $mat->ano_vigencia }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('matriz_id')" class="mt-2" />
                    </div>

                    {{-- Modalidade --}}
                    <div>
                        <x-input-label for="modalidade_id" :value="__('Modalidade de Ensino')" />
                        <select id="modalidade_id" name="modalidade_id" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                            @foreach ($modalidades as $mod)
                                <option value="{{ $mod->id }}" {{ old('modalidade_id', $turma->modalidade_id) == $mod->id ? 'selected' : '' }}>{{ $mod->nome }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('modalidade_id')" class="mt-2" />
                    </div>

                    {{-- Série / Etapa --}}
                    <div>
                        <x-input-label for="serie_etapa" :value="__('Série / Etapa')" />
                        <x-text-input id="serie_etapa" name="serie_etapa" type="text" class="mt-1 block w-full uppercase" :value="old('serie_etapa', $turma->serie_etapa)" required />
                        <x-input-error :messages="$errors->get('serie_etapa')" class="mt-2" />
                    </div>

                    {{-- Turno --}}
                    <div>
                        <x-input-label for="turno" :value="__('Turno')" />
                        <select id="turno" name="turno" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                            @foreach (['Matutino', 'Vespertino', 'Noturno', 'Integral'] as $turno)
                                <option value="{{ $turno }}" {{ old('turno', $turma->turno) == $turno ? 'selected' : '' }}>{{ $turno }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('turno')" class="mt-2" />
                    </div>

                    {{-- Vagas --}}
                    <div>
                        <x-input-label for="vagas" :value="__('Número de Vagas')" />
                        <x-text-input id="vagas" name="vagas" type="number" class="mt-1 block w-full" :value="old('vagas', $turma->vagas)" required />
                        <x-input-error :messages="$errors->get('vagas')" class="mt-2" />
                    </div>

                    {{-- Multisseriada --}}
                    <div class="flex items-center space-x-3 mt-8">
                        <input type="checkbox" id="is_multisseriada" name="is_multisseriada" value="1" {{ old('is_multisseriada', $turma->is_multisseriada) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                        <x-input-label for="is_multisseriada" :value="__('Esta turma é multisseriada?')" />
                    </div>
                </div>

                <div class="flex items-center justify-end border-t pt-8 space-x-6">
                <div class="flex items-center justify-end border-t pt-8 space-x-6 px-6">
                    <a href="{{ route('secretaria-escolar.turmas.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-500 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <x-primary-button class="py-3 px-8 text-sm" style="background-color: #059669;">{{ __('Atualizar Turma') }}</x-primary-button>
                </div>
            </form>
        </div>

</x-secretaria-escolar-layout>
