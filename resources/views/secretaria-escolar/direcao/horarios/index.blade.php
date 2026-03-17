<x-secretaria-escolar-layout>
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-600">Direcao Escolar</p>
                    <h1 class="mt-3 text-3xl font-outfit font-bold text-slate-900">Gestao de horarios</h1>
                    <p class="mt-2 text-sm text-slate-600">A direcao pode consultar, cadastrar, editar e reorganizar horarios de turma conforme as necessidades da escola.</p>
                </div>
                @can('cadastrar horarios da direcao')
                    <a href="{{ route('secretaria-escolar.direcao.horarios.create') }}" class="inline-flex items-center rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-indigo-700">
                        Nova grade da direcao
                    </a>
                @endcan
            </div>
        </section>

        <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('secretaria-escolar.direcao.horarios.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label for="escola_id" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Escola</label>
                    <select id="escola_id" name="escola_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todas</option>
                        @foreach ($filtros['escolas'] as $escola)
                            <option value="{{ $escola->id }}" @selected(request('escola_id') == $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="turma_id" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Turma</label>
                    <select id="turma_id" name="turma_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todas</option>
                        @foreach ($filtros['turmas'] as $turma)
                            <option value="{{ $turma->id }}" @selected(request('turma_id') == $turma->id)>{{ $turma->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="turno" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Turno</label>
                    <select id="turno" name="turno" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todos</option>
                        @foreach (['Matutino', 'Vespertino', 'Noturno', 'Integral'] as $turno)
                            <option value="{{ $turno }}" @selected(request('turno') === $turno)>{{ $turno }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="professor_id" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Professor</label>
                    <select id="professor_id" name="professor_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todos</option>
                        @foreach ($filtros['professores'] as $professor)
                            <option value="{{ $professor->id }}" @selected(request('professor_id') == $professor->id)>{{ $professor->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-slate-800">Filtrar</button>
                    <a href="{{ route('secretaria-escolar.direcao.horarios.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-slate-600 transition hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </section>

        <section class="rounded-[2rem] border border-indigo-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.18em] text-slate-500">
                            <th class="px-6 py-4">Turma</th>
                            <th class="px-6 py-4">Turno</th>
                            <th class="px-6 py-4">Dia</th>
                            <th class="px-6 py-4">Horario</th>
                            <th class="px-6 py-4">Disciplina</th>
                            <th class="px-6 py-4">Professor</th>
                            <th class="px-6 py-4 text-right">Acao</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($dias = [1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terca-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sabado'])
                        @forelse ($horarios as $horario)
                            <tr class="border-b border-slate-100">
                                <td class="px-6 py-4"><p class="font-semibold text-slate-900">{{ $horario->turma->nome }}</p><p class="mt-1 text-xs text-slate-500">{{ $horario->escola->nome }}</p></td>
                                <td class="px-6 py-4 text-slate-700">{{ $horario->turma->turno }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $dias[$horario->dia_semana] ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ \Carbon\Carbon::parse($horario->horario_inicial)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->horario_final)->format('H:i') }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $horario->disciplina->nome }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $horario->professor?->nome ?: 'Sem professor' }}</td>
                                <td class="px-6 py-4 text-right">
                                    @canany(['editar horarios da direcao', 'reorganizar horarios da direcao'])
                                        <a href="{{ route('secretaria-escolar.direcao.horarios.edit', $horario) }}" class="inline-flex items-center rounded-2xl border border-indigo-200 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 transition hover:bg-indigo-50">
                                            Editar / reorganizar
                                        </a>
                                    @endcanany
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-12 text-center text-slate-500">Nenhum horario encontrado para os filtros informados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($horarios->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">{{ $horarios->links() }}</div>
            @endif
        </section>
    </div>
</x-secretaria-escolar-layout>
