<x-secretaria-escolar-layout>
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-600">Direcao Escolar</p>
            <h1 class="mt-3 text-3xl font-outfit font-bold text-slate-900">Nova grade da direcao</h1>
            <p class="mt-2 text-sm text-slate-600">Monte ou reorganize a grade da turma conforme necessidade administrativa e pedagogica.</p>
        </section>

        <div class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm" x-data="horarioDirecaoForm()">
            <form action="{{ route('secretaria-escolar.direcao.horarios.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label for="escola_id" :value="__('Escola')" />
                        <select id="escola_id" name="escola_id" class="mt-1 block w-full rounded-xl border-slate-300" required>
                            <option value="">Selecione</option>
                            @foreach ($escolas as $escola)
                                <option value="{{ $escola->id }}">{{ $escola->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="turma_id" :value="__('Turma')" />
                        <select id="turma_id" name="turma_id" class="mt-1 block w-full rounded-xl border-slate-300" required>
                            <option value="">Selecione</option>
                            @foreach ($turmas as $turma)
                                <option value="{{ $turma->id }}">{{ $turma->nome }} - {{ $turma->turno }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Encontros da grade</h2>
                        <button type="button" @click="adicionar()" class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-slate-800">Adicionar aula</button>
                    </div>

                    <template x-for="(horario, index) in horarios" :key="index">
                        <div class="grid gap-4 rounded-[1.6rem] border border-slate-200 bg-slate-50 p-4 md:grid-cols-6">
                            <select :name="`horarios[${index}][dia_semana]`" x-model="horario.dia_semana" class="rounded-xl border-slate-300" required>
                                <option value="">Dia</option><option value="2">Segunda-feira</option><option value="3">Terca-feira</option><option value="4">Quarta-feira</option><option value="5">Quinta-feira</option><option value="6">Sexta-feira</option><option value="7">Sabado</option><option value="1">Domingo</option>
                            </select>
                            <input type="time" :name="`horarios[${index}][horario_inicial]`" x-model="horario.horario_inicial" class="rounded-xl border-slate-300" required>
                            <input type="time" :name="`horarios[${index}][horario_final]`" x-model="horario.horario_final" class="rounded-xl border-slate-300" required>
                            <select :name="`horarios[${index}][disciplina_id]`" x-model="horario.disciplina_id" class="rounded-xl border-slate-300" required>
                                <option value="">Disciplina</option>
                                @foreach ($disciplinas as $disciplina)
                                    <option value="{{ $disciplina->id }}">{{ $disciplina->nome }}</option>
                                @endforeach
                            </select>
                            <select :name="`horarios[${index}][professor_id]`" x-model="horario.professor_id" class="rounded-xl border-slate-300">
                                <option value="">Professor</option>
                                @foreach ($professores as $professor)
                                    <option value="{{ $professor->id }}">{{ $professor->nome }}</option>
                                @endforeach
                            </select>
                            <div class="flex gap-2">
                                <input type="number" min="1" :name="`horarios[${index}][ordem_aula]`" x-model="horario.ordem_aula" class="w-full rounded-xl border-slate-300" placeholder="Aula">
                                <button type="button" @click="remover(index)" class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold uppercase tracking-[0.18em] text-rose-700 transition hover:bg-rose-50">Remover</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('secretaria-escolar.direcao.horarios.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-slate-600 transition hover:bg-slate-50">Cancelar</a>
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-indigo-700">Salvar grade</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function horarioDirecaoForm() {
                return {
                    horarios: [{ dia_semana: '', horario_inicial: '', horario_final: '', disciplina_id: '', professor_id: '', ordem_aula: '' }],
                    adicionar() {
                        this.horarios.push({ dia_semana: '', horario_inicial: '', horario_final: '', disciplina_id: '', professor_id: '', ordem_aula: '' });
                    },
                    remover(index) {
                        this.horarios.splice(index, 1);
                    },
                };
            }
        </script>
    @endpush
</x-secretaria-escolar-layout>
