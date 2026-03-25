<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Registro sigiloso</p>
                <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Demandas</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('psicologia.demandas.create') }}" class="rounded-xl border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    Nova demanda
                </a>
            </div>
        </div>

        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" class="mb-6 grid gap-4 md:grid-cols-4">
                <select name="escola_id" class="rounded-xl border-slate-300 shadow-sm">
                    <option value="">Todas escolas</option>
                    @foreach ($escolas as $escola)
                        <option value="{{ $escola->id }}" {{ ($filtros['escola_id'] ?? '') == $escola->id ? 'selected' : '' }}>
                            {{ $escola->nome }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="rounded-xl border-slate-300 shadow-sm">
                    <option value="">Status de demanda</option>
                    <option value="aberta" {{ ($filtros['status'] ?? '') == 'aberta' ? 'selected' : '' }}>Aberta</option>
                    <option value="em_triagem" {{ ($filtros['status'] ?? '') == 'em_triagem' ? 'selected' : '' }}>Em triagem</option>
                    <option value="observacao" {{ ($filtros['status'] ?? '') == 'observacao' ? 'selected' : '' }}>Observacao</option>
                    <option value="encerrada" {{ ($filtros['status'] ?? '') == 'encerrada' ? 'selected' : '' }}>Encerrada</option>
                </select>
                <select name="prioridade" class="rounded-xl border-slate-300 shadow-sm">
                    <option value="">Todas prioridades</option>
                    <option value="baixa" {{ ($filtros['prioridade'] ?? '') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="media" {{ ($filtros['prioridade'] ?? '') == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta" {{ ($filtros['prioridade'] ?? '') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="urgente" {{ ($filtros['prioridade'] ?? '') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
                <button type="submit" class="rounded-xl border border-amber-600 bg-amber-500 px-4 py-2 text-sm font-semibold text-black shadow-sm transition-colors duration-200 hover:bg-amber-600 hover:text-white hover:shadow-md">
                    Filtrar
                </button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left">
                            <th class="pb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Prioridade</th>
                            <th class="pb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Pessoa</th>
                            <th class="pb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Tipo</th>
                            <th class="pb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Origem</th>
                            <th class="pb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="pb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Data</th>
                            <th class="pb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Acoes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($demandas as $demanda)
                            <tr class="hover:bg-slate-50">
                                <td class="py-4">
                                    @if ($demanda->prioridade === 'urgente')
                                        <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">Urgente</span>
                                    @elseif ($demanda->prioridade === 'alta')
                                        <span class="rounded-full bg-orange-100 px-2 py-1 text-xs font-semibold text-orange-700">Alta</span>
                                    @elseif ($demanda->prioridade === 'media')
                                        <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">Media</span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">Baixa</span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <p class="font-semibold text-[#14363a]">{{ $demanda->nome_atendido }}</p>
                                    <p class="text-xs text-slate-500">{{ ucfirst($demanda->tipo_publico) }}</p>
                                </td>
                                <td class="py-4 text-sm text-slate-600">
                                    {{ ucfirst($demanda->tipo_atendimento) }}
                                </td>
                                <td class="py-4 text-sm text-slate-600">
                                    {{ ucfirst(str_replace('_', ' ', $demanda->origem_demanda)) }}
                                </td>
                                <td class="py-4">
                                    @if ($demanda->status === 'aberta')
                                        <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">Aberta</span>
                                    @elseif ($demanda->status === 'em_triagem')
                                        <span class="rounded-full bg-purple-100 px-2 py-1 text-xs font-semibold text-purple-700">Triagem</span>
                                    @elseif ($demanda->status === 'em_atendimento')
                                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Atendimento</span>
                                    @elseif ($demanda->status === 'observacao')
                                        <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">Observacao</span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">Encerrada</span>
                                    @endif
                                </td>
                                <td class="py-4 text-sm text-slate-500">
                                    {{ $demanda->data_solicitacao->format('d/m/Y') }}
                                </td>
                                <td class="py-4">
                                    <a href="{{ route('psicologia.demandas.show', $demanda) }}" class="text-sm font-semibold text-cyan-600 hover:text-cyan-800">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm text-slate-500">
                                    Nenhuma demanda registrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $demandas->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-psicologia-layout>
