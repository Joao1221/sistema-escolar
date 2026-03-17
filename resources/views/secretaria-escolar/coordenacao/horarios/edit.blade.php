<x-secretaria-escolar-layout>
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-600">Coordenacao Pedagogica</p>
            <h1 class="mt-3 text-3xl font-outfit font-bold text-slate-900">Editar e reorganizar horario</h1>
            <p class="mt-2 text-sm text-slate-600">Ajuste dia, ordem, componente e professor responsavel conforme a estrategia pedagogica da escola.</p>
        </section>

        <div class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
            <form action="{{ route('secretaria-escolar.coordenacao.horarios.update', $horario) }}" method="POST" class="grid gap-6 md:grid-cols-2">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="escola_id" :value="__('Escola')" />
                    <select id="escola_id" name="escola_id" class="mt-1 block w-full rounded-xl border-slate-300" required>
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" @selected(old('escola_id', $horario->escola_id) == $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="turma_id" :value="__('Turma')" />
                    <select id="turma_id" name="turma_id" class="mt-1 block w-full rounded-xl border-slate-300" required>
                        @foreach ($turmas as $turma)
                            <option value="{{ $turma->id }}" @selected(old('turma_id', $horario->turma_id) == $turma->id)>{{ $turma->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="dia_semana" :value="__('Dia da semana')" />
                    <select id="dia_semana" name="dia_semana" class="mt-1 block w-full rounded-xl border-slate-300" required>
                        @foreach ([1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terca-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sabado'] as $valor => $rotulo)
                            <option value="{{ $valor }}" @selected(old('dia_semana', $horario->dia_semana) == $valor)>{{ $rotulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label for="horario_inicial" :value="__('Horario inicial')" />
                        <input id="horario_inicial" name="horario_inicial" type="time" value="{{ old('horario_inicial', \Carbon\Carbon::parse($horario->horario_inicial)->format('H:i')) }}" class="mt-1 block w-full rounded-xl border-slate-300" required>
                    </div>
                    <div>
                        <x-input-label for="horario_final" :value="__('Horario final')" />
                        <input id="horario_final" name="horario_final" type="time" value="{{ old('horario_final', \Carbon\Carbon::parse($horario->horario_final)->format('H:i')) }}" class="mt-1 block w-full rounded-xl border-slate-300" required>
                    </div>
                </div>
                <div>
                    <x-input-label for="disciplina_id" :value="__('Disciplina')" />
                    <select id="disciplina_id" name="disciplina_id" class="mt-1 block w-full rounded-xl border-slate-300" required>
                        @foreach ($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}" @selected(old('disciplina_id', $horario->disciplina_id) == $disciplina->id)>{{ $disciplina->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="professor_id" :value="__('Professor')" />
                    <select id="professor_id" name="professor_id" class="mt-1 block w-full rounded-xl border-slate-300">
                        <option value="">Sem professor</option>
                        @foreach ($professores as $professor)
                            <option value="{{ $professor->id }}" @selected(old('professor_id', $horario->professor_id) == $professor->id)>{{ $professor->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="ordem_aula" :value="__('Ordem da aula')" />
                    <input id="ordem_aula" name="ordem_aula" type="number" min="1" value="{{ old('ordem_aula', $horario->ordem_aula) }}" class="mt-1 block w-full rounded-xl border-slate-300">
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="ativo" value="0">
                    <input id="ativo" name="ativo" type="checkbox" value="1" class="rounded border-slate-300 text-emerald-600" @checked(old('ativo', $horario->ativo))>
                    <label for="ativo" class="text-sm text-slate-700">Horario ativo</label>
                </div>
                <div class="md:col-span-2 flex justify-end gap-3">
                    <a href="{{ route('secretaria-escolar.coordenacao.horarios.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-slate-600 transition hover:bg-slate-50">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-emerald-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-emerald-700">
                        Salvar ajustes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-secretaria-escolar-layout>
