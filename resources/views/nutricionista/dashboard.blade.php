<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Escolas monitoradas</p>
                <p class="mt-4 text-4xl font-bold text-[#17332a] font-fraunces">{{ $totais['escolas_monitoradas'] }}</p>
            </div>
            <div class="rounded-3xl border border-lime-100 bg-gradient-to-br from-lime-50 to-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-lime-700">Alimentos ativos</p>
                <p class="mt-4 text-4xl font-bold text-[#17332a] font-fraunces">{{ $totais['alimentos_ativos'] }}</p>
            </div>
            <div class="rounded-3xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Movimentacoes do mes</p>
                <p class="mt-4 text-4xl font-bold text-[#17332a] font-fraunces">{{ $totais['movimentacoes_mes'] }}</p>
            </div>
            <div class="rounded-3xl border border-rose-100 bg-gradient-to-br from-rose-50 to-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-rose-700">Validades criticas</p>
                <p class="mt-4 text-4xl font-bold text-[#17332a] font-fraunces">{{ $totais['validades_criticas'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
            <section class="rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-xl font-bold text-[#17332a] font-fraunces">Comparativo inicial entre escolas</h2>
                    <p class="mt-1 text-sm text-slate-500">Leitura gerencial da operacao e dos alertas por unidade.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Escola</th>
                                <th class="px-6 py-3 text-right">Entradas</th>
                                <th class="px-6 py-3 text-right">Saidas</th>
                                <th class="px-6 py-3 text-right">Baixo estoque</th>
                                <th class="px-6 py-3 text-right">Validades</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($comparativoEscolas as $item)
                                <tr class="hover:bg-emerald-50/40">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $item->escola->nome }}</div>
                                        <div class="text-xs text-slate-500">Cardapio recente: {{ $item->cardapio_recente ? \Carbon\Carbon::parse($item->cardapio_recente)->format('d/m/Y') : 'Sem lancamento' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-slate-700">{{ number_format($item->entradas, 3, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right text-slate-700">{{ number_format($item->saidas, 3, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-semibold {{ $item->baixo_estoque > 0 ? 'text-amber-700' : 'text-emerald-700' }}">{{ $item->baixo_estoque }}</td>
                                    <td class="px-6 py-4 text-right font-semibold {{ $item->validades_criticas > 0 ? 'text-rose-700' : 'text-slate-700' }}">{{ $item->validades_criticas }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-xl font-bold text-[#17332a] font-fraunces">Top saidas</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($topSaidas as $item)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $item->alimento?->nome ?? 'Alimento removido' }}</p>
                                        <p class="text-xs text-slate-500">Saida acumulada</p>
                                    </div>
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ number_format((float) $item->total_saida, 3, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="px-6 py-8 text-sm text-slate-500">Sem saidas consolidadas ainda.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-xl font-bold text-[#17332a] font-fraunces">Alertas de validade</h2>
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
                            <p class="px-6 py-8 text-sm text-slate-500">Nenhum alerta de validade no momento.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-nutricionista-layout>
