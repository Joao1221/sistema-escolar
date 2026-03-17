<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm">
            <form method="GET" action="{{ route('nutricionista.estoque.index') }}" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Escola</label>
                    <select name="escola_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todas</option>
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" @selected(($filtros['escola_id'] ?? null) == $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Alimento</label>
                    <select name="alimento_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        @foreach ($alimentos as $alimento)
                            <option value="{{ $alimento->id }}" @selected(($filtros['alimento_id'] ?? null) == $alimento->id)>{{ $alimento->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="w-full rounded-xl bg-[#17332a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#22473b]">Filtrar</button>
                    <a href="{{ route('nutricionista.estoque.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <div class="rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Escola</th>
                                <th class="px-6 py-3">Alimento</th>
                                <th class="px-6 py-3">Categoria</th>
                                <th class="px-6 py-3 text-right">Saldo</th>
                                <th class="px-6 py-3 text-right">Minimo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($estoque as $item)
                                <tr class="hover:bg-emerald-50/40">
                                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->escola?->nome }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $item->alimento?->nome }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $item->alimento?->categoria?->nome }}</td>
                                    <td class="px-6 py-4 text-right font-semibold {{ $item->abaixo_minimo ? 'text-amber-700' : 'text-slate-900' }}">{{ number_format((float) $item->saldo_atual, 3, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right text-slate-600">{{ number_format((float) ($item->alimento?->estoque_minimo ?? 0), 3, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-xl font-bold text-[#17332a] font-fraunces">Validades monitoradas</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($alertasValidade as $alerta)
                        <div class="px-6 py-4">
                            <p class="font-semibold text-slate-900">{{ $alerta->alimento?->nome }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $alerta->escola?->nome }}</p>
                            <p class="mt-2 text-sm {{ $alerta->data_validade && $alerta->data_validade->isPast() ? 'text-rose-700' : 'text-amber-700' }}">
                                {{ optional($alerta->data_validade)->format('d/m/Y') ?: 'Sem validade' }}
                            </p>
                        </div>
                    @empty
                        <p class="px-6 py-8 text-sm text-slate-500">Sem alertas cadastrados.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-nutricionista-layout>
