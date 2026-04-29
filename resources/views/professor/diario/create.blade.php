<x-professor-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="max-w-4xl space-y-8">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-amber-700 font-semibold">Portal do Professor</p>
            <h2 class="text-2xl font-outfit font-bold sm:text-3xl" style="color:#f3efff;">Abrir novo diário</h2>
            <p class="mt-2" style="color:rgba(239,238,255,0.78);">O diário só pode ser aberto para combinações já vinculadas ao horário do professor.</p>
        </div>

        @if ($opcoesCriacao->isEmpty())
            <div class="rounded-[1.8rem] border border-amber-200 bg-amber-50 p-6 text-amber-900">
                Não foram encontrados horários vinculados ao professor autenticado. Primeiro associe o professor a turmas, disciplinas e horários.
            </div>
        @endif

        <div class="rounded-[2rem] bg-white border border-[#e2d3bf] shadow-sm p-6 lg:p-8">
            <form method="POST" action="{{ route('professor.diario.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="escola_id" :value="__('Escola')" />
                        <select id="escola_id" name="escola_id" class="mt-1 block w-full rounded-xl border-stone-300">
                            <option value="">Selecione</option>
                            @foreach ($opcoesCriacao->unique('escola_id') as $opcao)
                                <option value="{{ $opcao->escola_id }}" @selected(old('escola_id') == $opcao->escola_id)>{{ $opcao->escola->nome }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('escola_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="turma_id" :value="__('Turma')" />
                        <select id="turma_id" name="turma_id" class="mt-1 block w-full rounded-xl border-stone-300">
                            <option value="">Selecione</option>
                            @foreach ($opcoesCriacao->unique('turma_id') as $opcao)
                                <option value="{{ $opcao->turma_id }}" @selected(old('turma_id') == $opcao->turma_id)>{{ $opcao->turma->nome }} - {{ $opcao->turma->turno }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('turma_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="disciplina_id" :value="__('Disciplina')" />
                        <select id="disciplina_id" name="disciplina_id" class="mt-1 block w-full rounded-xl border-stone-300">
                            <option value="">Selecione</option>
                            @foreach ($opcoesCriacao->unique('disciplina_id') as $opcao)
                                <option value="{{ $opcao->disciplina_id }}" @selected(old('disciplina_id') == $opcao->disciplina_id)>{{ $opcao->disciplina->nome }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('disciplina_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="ano_letivo" :value="__('Ano Letivo')" />
                        <x-text-input id="ano_letivo" name="ano_letivo" type="number" class="mt-1 block w-full" :value="old('ano_letivo', $anoAtual)" />
                        <x-input-error :messages="$errors->get('ano_letivo')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="periodo_tipo" :value="__('Tipo de Periodo')" />
                        <select id="periodo_tipo" name="periodo_tipo" class="mt-1 block w-full rounded-xl border-stone-300">
                            @foreach (['bimestre' => 'Bimestre', 'trimestre' => 'Trimestre', 'semestre' => 'Semestre', 'anual' => 'Anual', 'etapa' => 'Etapa'] as $valor => $rotulo)
                                <option value="{{ $valor }}" @selected(old('periodo_tipo', 'bimestre') === $valor)>{{ $rotulo }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('periodo_tipo')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="periodo_referencia" :value="__('Referencia do Periodo')" />
                        <x-text-input id="periodo_referencia" name="periodo_referencia" type="text" class="mt-1 block w-full" :value="old('periodo_referencia', '1')" />
                        <x-input-error :messages="$errors->get('periodo_referencia')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="observacoes_gerais" :value="__('Observações Iniciais')" />
                    <textarea id="observacoes_gerais" name="observacoes_gerais" rows="4" class="mt-1 block w-full rounded-xl border-stone-300 shadow-sm">{{ old('observacoes_gerais') }}</textarea>
                    <x-input-error :messages="$errors->get('observacoes_gerais')" class="mt-2" />
                </div>

                <div class="flex flex-wrap gap-3 pt-4">
                    <a href="{{ route('professor.diario.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-[#d0b49a] px-4 py-3 text-sm font-semibold text-[#7b4b2a] hover:bg-[#fffaf4] transition sm:w-auto">
                        Voltar
                    </a>
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[#8b4d28] px-5 py-3 text-sm font-bold uppercase tracking-widest text-white hover:bg-[#6f3c20] transition sm:w-auto">
                        Criar diário
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-professor-layout>
