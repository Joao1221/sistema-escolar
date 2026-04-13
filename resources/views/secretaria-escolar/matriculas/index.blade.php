<x-secretaria-escolar-layout>
    <div class="px-4 lg:px-0">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Matrículas da Escola</h1>
                <p class="mt-1 text-sm md:text-base text-slate-500">
                    Acompanhe matrículas ativas, enturmações e movimentações da sua unidade.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                @can('cadastrar matrícula')
                    <a href="{{ route('secretaria-escolar.matriculas.create') }}"
                       class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white shadow-sm transition hover:bg-emerald-700">
                        + Nova Matrícula
                    </a>
                @endcan

                <a href="{{ route('secretaria-escolar.turmas.index') }}"
                   class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Ver Turmas
                </a>

                <a href="{{ route('secretaria-escolar.alunos.create') }}"
                   class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Novo Aluno
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Matrículas ativas</p>
                <p class="mt-3 text-4xl font-bold text-slate-900 font-outfit">{{ $stats['ativas'] }}</p>
                <p class="mt-2 text-xs font-medium text-slate-500">Todas as matrículas com status ativo</p>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Sem turma</p>
                <p class="mt-3 text-4xl font-bold text-slate-900 font-outfit">{{ $stats['sem_turma'] }}</p>
                <p class="mt-2 text-xs font-medium text-slate-500">Alunos prontos para enturmação</p>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Regulares</p>
                <p class="mt-3 text-4xl font-bold text-slate-900 font-outfit">{{ $stats['regular'] }}</p>
                <p class="mt-2 text-xs font-medium text-slate-500">Matrículas do tipo regular</p>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">AEE</p>
                <p class="mt-3 text-4xl font-bold text-slate-900 font-outfit">{{ $stats['aee'] }}</p>
                <p class="mt-2 text-xs font-medium text-slate-500">Matrículas do atendimento especializado</p>
            </div>
        </div>

        <div class="mb-6 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Filtros</h2>
                    <p class="text-sm text-slate-500">Use a turma para listar todos os alunos da mesma sala.</p>
                </div>

                <a href="{{ route('secretaria-escolar.matriculas.index') }}"
                   class="inline-flex items-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold uppercase tracking-widest text-slate-600 transition hover:bg-slate-50">
                    Limpar filtros
                </a>
            </div>

            <form action="{{ route('secretaria-escolar.matriculas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                <div class="xl:col-span-2">
                    <x-input-label for="aluno_nome" :value="__('Nome do Aluno')" />
                    <x-text-input id="aluno_nome" name="aluno_nome" type="text" class="mt-1 block w-full uppercase" :value="request('aluno_nome')" placeholder="Digite o nome do aluno..." />
                </div>

                <div>
                    <x-input-label for="turma_id" :value="__('Turma')" />
                    <select id="turma_id" name="turma_id" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todas as turmas</option>
                        <option value="__sem_turma" @selected(request('turma_id') === '__sem_turma')>Sem turma</option>
                        @foreach ($turmas as $turma)
                            <option value="{{ $turma->id }}" @selected((string) request('turma_id') === (string) $turma->id)>
                                {{ $turma->nome }} @if($turma->turno) - {{ $turma->turno }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        @foreach (['ativa', 'concluida', 'cancelada', 'transferida', 'rematriculada'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ strtoupper($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="tipo" :value="__('Tipo')" />
                    <select id="tipo" name="tipo" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        <option value="regular" @selected(request('tipo') === 'regular')>REGULAR</option>
                        <option value="aee" @selected(request('tipo') === 'aee')>AEE</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="ano_letivo" :value="__('Ano Letivo')" />
                    <select id="ano_letivo" name="ano_letivo" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        @foreach ($anosDisponiveis as $ano)
                            <option value="{{ $ano }}" @selected((string) request('ano_letivo') === (string) $ano)>{{ $ano }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="xl:col-span-5 flex items-end gap-3">
                    <x-primary-button style="background-color: #059669;">
                        Filtrar
                    </x-primary-button>
                    <a href="{{ route('secretaria-escolar.matriculas.index') }}"
                       class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-600 shadow-sm transition hover:bg-slate-50">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Lista de Matrículas</h2>
                        <p class="text-sm text-slate-500">{{ $matriculas->total() }} matrícula(s) encontrada(s)</p>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Ações por matrícula</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Aluno / RGM</th>
                            <th class="px-6 py-4">Turma / Ano</th>
                            <th class="px-6 py-4 text-center">Tipo</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Data</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($matriculas as $matricula)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 uppercase">{{ $matricula->aluno->nome_completo }}</div>
                                    <div class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-600">
                                        RGM: {{ $matricula->aluno->rgm ?? 'N/D' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-bold uppercase text-slate-800">
                                        {{ $matricula->turma?->nome ?? 'Sem turma' }}
                                    </div>
                                    <div class="mt-1 text-[10px] font-medium text-slate-500">
                                        Ano letivo: {{ $matricula->ano_letivo }}
                                        @if($matricula->turma?->turno)
                                            <span class="mx-1">•</span>{{ $matricula->turma->turno }}
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest',
                                        'bg-blue-100 text-blue-800' => $matricula->tipo === 'regular',
                                        'bg-purple-100 text-purple-800' => $matricula->tipo === 'aee',
                                    ])>
                                        {{ strtoupper($matricula->tipo) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest',
                                        'bg-emerald-100 text-emerald-800' => $matricula->status === 'ativa',
                                        'bg-blue-100 text-blue-800' => $matricula->status === 'concluida',
                                        'bg-amber-100 text-amber-800' => $matricula->status === 'transferida',
                                        'bg-red-100 text-red-800' => $matricula->status === 'cancelada',
                                        'bg-violet-100 text-violet-800' => $matricula->status === 'rematriculada',
                                    ])>
                                        {{ strtoupper($matricula->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-slate-500">
                                    {{ optional($matricula->data_matricula)->format('d/m/Y') ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('secretaria-escolar.matriculas.show', $matricula) }}"
                                           class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200">
                                            Ver
                                        </a>

                                        @if ($matricula->status === 'ativa' && ! $matricula->turma_id)
                                            <a href="{{ route('secretaria-escolar.matriculas.enturmar.form', $matricula) }}"
                                               class="inline-flex items-center rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                                                Enturmar
                                            </a>
                                        @endif

                                        @if ($matricula->status === 'ativa')
                                            <a href="{{ route('secretaria-escolar.matriculas.transferir.form', $matricula) }}"
                                               class="inline-flex items-center rounded-lg bg-orange-50 px-3 py-1.5 text-xs font-bold text-orange-700 transition hover:bg-orange-100">
                                                Transferir
                                            </a>
                                        @endif

                                        @if ($matricula->status === 'concluida')
                                            <a href="{{ route('secretaria-escolar.matriculas.rematricular.form', $matricula) }}"
                                               class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 transition hover:bg-indigo-100">
                                                Rematricular
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900">Nenhuma matrícula encontrada</h3>
                                        <p class="mt-2 text-sm text-slate-500">Tente ajustar os filtros ou criar uma nova matrícula para esta escola.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-6 py-4">
                {{ $matriculas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-secretaria-escolar-layout>
