<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Acompanhamento tecnico</p>
                <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Planos de intervencao</h1>
                <p class="mt-2 text-sm text-slate-500">Monitoramento dos planos ativos, concluidos e em acompanhamento.</p>
            </div>
        </div>

        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('psicologia.planos.index') }}" class="grid gap-4 md:grid-cols-3">
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
                        @foreach (['ativo', 'em_acompanhamento', 'concluido'] as $status)
                            <option value="{{ $status }}" @selected(($filtros['status'] ?? null) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="w-full rounded-xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">Filtrar</button>
                    <a href="{{ route('psicologia.planos.index') }}" class="rounded-xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">Limpar</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Inicio</th>
                            <th class="px-6 py-3">Atendimento</th>
                            <th class="px-6 py-3">Escola</th>
                            <th class="px-6 py-3">Objetivo geral</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($planos as $plano)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-700">{{ $plano->data_inicio?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-slate-700">
                                    <a href="{{ route('psicologia.show', $plano->atendimento) }}" class="font-semibold text-[#14363a] hover:text-cyan-700">{{ $plano->atendimento?->nome_atendido }}</a>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $plano->atendimento?->escola?->nome }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ \Illuminate\Support\Str::limit($plano->objetivo_geral, 90) }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst(str_replace('_', ' ', $plano->status)) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Nenhum plano encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">{{ $planos->links() }}</div>
        </div>
    </div>
</x-psicologia-layout>
