<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Psicologia/Psicopedagogia</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Demandas psicossociais da escola</h1>
                <p class="mt-2 text-sm text-slate-500">A escola registra a demanda, a equipe psicossocial assume o caso e as devolutivas retornam por aqui.</p>
            </div>
            @can('registrar demandas psicossociais escolares')
                <a href="{{ route('secretaria-escolar.demandas-psicossociais.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Nova demanda</a>
            @endcan
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('secretaria-escolar.demandas-psicossociais.index') }}" class="grid gap-4 md:grid-cols-5">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Escola</label>
                    <select name="escola_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Todas</option>
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" @selected(($filtros['escola_id'] ?? null) == $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Status</label>
                    <select name="status" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Todos</option>
                        @foreach (['aberta', 'em_triagem', 'em_atendimento', 'observacao', 'encerrada'] as $status)
                            <option value="{{ $status }}" @selected(($filtros['status'] ?? null) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Publico</label>
                    <select name="tipo_publico" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Todos</option>
                        @foreach (['aluno', 'professor', 'funcionario', 'responsavel', 'coletivo'] as $tipo)
                            <option value="{{ $tipo }}" @selected(($filtros['tipo_publico'] ?? null) === $tipo)>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Area tecnica</label>
                    <select name="tipo_atendimento" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Todas</option>
                        @foreach (['psicologia', 'psicopedagogia', 'psicossocial'] as $tipoAtendimento)
                            <option value="{{ $tipoAtendimento }}" @selected(($filtros['tipo_atendimento'] ?? null) === $tipoAtendimento)>{{ ucfirst($tipoAtendimento) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filtrar</button>
                    <a href="{{ route('secretaria-escolar.demandas-psicossociais.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Demanda</th>
                            <th class="px-6 py-3">Escola</th>
                            <th class="px-6 py-3">Area</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Profissional</th>
                            <th class="px-6 py-3">Devolutivas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($demandas as $demanda)
                            @php
                                $statusClasses = match ($demanda->status) {
                                    'aberta' => 'bg-sky-100 text-sky-700',
                                    'em_triagem' => 'bg-violet-100 text-violet-700',
                                    'em_atendimento' => 'bg-emerald-100 text-emerald-700',
                                    'encerrada' => 'bg-slate-100 text-slate-600',
                                    default => 'bg-amber-100 text-amber-700',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 align-top">
                                    <a href="{{ route('secretaria-escolar.demandas-psicossociais.show', $demanda) }}" class="font-semibold text-slate-900 hover:text-emerald-700">
                                        {{ $demanda->nome_atendido ?? 'Demanda sem identificado' }}
                                    </a>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ ucfirst($demanda->tipo_publico) }} |
                                        {{ ucfirst(str_replace('_', ' ', $demanda->origem_demanda)) }} |
                                        {{ $demanda->data_solicitacao?->format('d/m/Y') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 align-top text-slate-700">{{ $demanda->escola?->nome }}</td>
                                <td class="px-6 py-4 align-top text-slate-700">{{ ucfirst($demanda->tipo_atendimento) }}</td>
                                <td class="px-6 py-4 align-top">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                        {{ ucfirst(str_replace('_', ' ', $demanda->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top text-slate-700">
                                    {{ $demanda->atendimento?->profissionalResponsavel?->nome ?? 'Aguardando atribuicao' }}
                                </td>
                                <td class="px-6 py-4 align-top text-slate-700">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ (int) ($demanda->devolutivas_escolares_count ?? 0) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">Nenhuma demanda psicossocial escolar encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($demandas->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $demandas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-secretaria-escolar-layout>
