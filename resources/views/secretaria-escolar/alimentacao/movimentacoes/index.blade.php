<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Movimentacoes de alimentos</h1>
                <p class="mt-2 text-sm text-slate-500">Acompanhe entradas, saidas e saldos registrados pela escola.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('secretaria-escolar.alimentacao.movimentacoes.create', ['tipo' => 'entrada']) }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Nova entrada</a>
                <a href="{{ route('secretaria-escolar.alimentacao.movimentacoes.create', ['tipo' => 'saida']) }}" class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">Nova saida</a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('secretaria-escolar.alimentacao.movimentacoes.index') }}" class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Escola</label>
                    <select name="escola_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todas</option>
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" @selected(($filtros['escola_id'] ?? null) == $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Tipo</label>
                    <select name="tipo" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        <option value="entrada" @selected(($filtros['tipo'] ?? null) === 'entrada')>Entrada</option>
                        <option value="saida" @selected(($filtros['tipo'] ?? null) === 'saida')>Saida</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Alimento</label>
                    <select name="alimento_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        @foreach ($alimentos as $alimento)
                            <option value="{{ $alimento->id }}" @selected(($filtros['alimento_id'] ?? null) == $alimento->id)>{{ $alimento->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filtrar</button>
                    <a href="{{ route('secretaria-escolar.alimentacao.movimentacoes.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Escola</th>
                            <th class="px-6 py-3">Alimento</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3 text-right">Quantidade</th>
                            <th class="px-6 py-3 text-right">Saldo</th>
                            <th class="px-6 py-3">Validade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($movimentacoes as $movimentacao)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-600">{{ $movimentacao->data_movimentacao->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $movimentacao->escola?->nome }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $movimentacao->alimento?->nome }}</div>
                                    <div class="text-xs text-slate-500">{{ $movimentacao->fornecedor?->nome ?: 'Sem fornecedor' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $movimentacao->tipo === 'entrada' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ucfirst($movimentacao->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-700">{{ number_format((float) $movimentacao->quantidade, 3, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-900">{{ number_format((float) $movimentacao->saldo_resultante, 3, ',', '.') }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ optional($movimentacao->data_validade)->format('d/m/Y') ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">Nenhuma movimentacao encontrada.</td>
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
</x-secretaria-escolar-layout>
