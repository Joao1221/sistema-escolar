<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Gestao de estoque</p>
                <h2 class="mt-2 text-2xl font-bold text-[#17332a] font-fraunces">Entradas e saídas por escola</h2>
                <p class="mt-2 text-sm text-slate-500">Acompanhe as movimentacoes e, quando necessario, registre novos lancamentos diretamente neste portal.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('nutricionista.movimentacoes.create', ['tipo' => 'entrada']) }}" class="inline-flex items-center justify-center rounded-2xl bg-[#17332a] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#22473b]">
                    Nova entrada
                </a>
                <a href="{{ route('nutricionista.movimentacoes.create', ['tipo' => 'saida']) }}" class="inline-flex items-center justify-center rounded-2xl border border-amber-300 bg-amber-50 px-5 py-3 text-sm font-bold text-amber-800 transition hover:bg-amber-100">
                    Nova saida
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm">
            <form method="GET" action="{{ route('nutricionista.movimentacoes.index') }}" class="grid gap-4 md:grid-cols-4">
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
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tipo</label>
                    <select name="tipo" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        <option value="entrada" @selected(($filtros['tipo'] ?? null) === 'entrada')>Entrada</option>
                        <option value="saida" @selected(($filtros['tipo'] ?? null) === 'saida')>Saida</option>
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
                    <a href="{{ route('nutricionista.movimentacoes.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Escola</th>
                            <th class="px-6 py-3">Alimento</th>
                            <th class="px-6 py-3">Fornecedor</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3 text-right">Quantidade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($movimentacoes as $movimentacao)
                            <tr class="hover:bg-emerald-50/40">
                                <td class="px-6 py-4 text-slate-600">{{ $movimentacao->data_movimentacao->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $movimentacao->escola?->nome }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $movimentacao->alimento?->nome }}</div>
                                    <div class="text-xs text-slate-500">{{ $movimentacao->alimento?->categoria?->nome }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $movimentacao->fornecedor?->nome ?: 'Nao informado' }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $movimentacao->tipo === 'entrada' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($movimentacao->tipo) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-900">{{ number_format((float) $movimentacao->quantidade, 3, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                    Nenhuma movimentacao encontrada para os filtros informados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($movimentacoes->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $movimentacoes->links() }}
                </div>
            @endif
        </div>
    </div>
</x-nutricionista-layout>
