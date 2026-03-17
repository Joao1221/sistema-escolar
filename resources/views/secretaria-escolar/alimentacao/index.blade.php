<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Alimentacao Escolar</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Operacao diaria da merenda escolar</h1>
                <p class="mt-2 text-sm text-slate-500">Controle de estoque, validade, movimentacoes e cardapio por escola.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('secretaria-escolar.alimentacao.movimentacoes.create', ['tipo' => 'entrada']) }}" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-700">
                    Registrar entrada
                </a>
                <a href="{{ route('secretaria-escolar.alimentacao.cardapios.create') }}" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Lancar cardapio
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('secretaria-escolar.alimentacao.index') }}" class="flex flex-col gap-3 md:flex-row md:items-end">
                <div class="w-full md:max-w-sm">
                    <label for="escola_id" class="text-xs font-semibold uppercase tracking-widest text-slate-500">Escola</label>
                    <select id="escola_id" name="escola_id" class="mt-2 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" @selected($escolaSelecionada === $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Atualizar painel
                </button>
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Alimentos ativos</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $indicadores['total_alimentos'] }}</p>
            </div>
            <div class="rounded-3xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-sky-700">Categorias</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $indicadores['total_categorias'] }}</p>
            </div>
            <div class="rounded-3xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-700">Baixo estoque</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $indicadores['baixo_estoque'] }}</p>
            </div>
            <div class="rounded-3xl border border-rose-100 bg-gradient-to-br from-rose-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-rose-700">Vencendo em 30 dias</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $indicadores['vencendo'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.6fr_1fr]">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Estoque atual</h2>
                        <p class="text-sm text-slate-500">Visao consolidada por alimento.</p>
                    </div>
                    <a href="{{ route('secretaria-escolar.alimentacao.movimentacoes.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Ver movimentacoes</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Alimento</th>
                                <th class="px-6 py-3">Categoria</th>
                                <th class="px-6 py-3 text-right">Saldo</th>
                                <th class="px-6 py-3 text-right">Minimo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($estoque as $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $item->nome }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->unidade_medida }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $item->categoria?->nome ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-semibold {{ $item->abaixo_minimo ? 'text-rose-600' : 'text-slate-900' }}">{{ number_format($item->saldo_atual, 3, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-slate-600">{{ number_format((float) $item->estoque_minimo, 3, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-500">Nenhum alimento cadastrado ainda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Validades</h2>
                        <p class="text-sm text-slate-500">Entradas com data de validade mais proxima.</p>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($itensValidade as $item)
                            <div class="px-6 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $item->alimento?->nome }}</p>
                                        <p class="text-xs text-slate-500">Lote {{ $item->lote ?: 'nao informado' }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ optional($item->data_validade)->isPast() ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ optional($item->data_validade)->format('d/m/Y') ?: '-' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="px-6 py-8 text-sm text-slate-500">Sem registros de validade no momento.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Cardapios recentes</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($cardapiosRecentes as $cardapio)
                            <a href="{{ route('secretaria-escolar.alimentacao.cardapios.show', $cardapio) }}" class="block px-6 py-4 transition hover:bg-slate-50">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $cardapio->data_cardapio->format('d/m/Y') }}</p>
                                        <p class="text-xs text-slate-500">{{ $cardapio->itens->count() }} itens cadastrados</p>
                                    </div>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Abrir</span>
                                </div>
                            </a>
                        @empty
                            <p class="px-6 py-8 text-sm text-slate-500">Nenhum cardapio lancado.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">Movimentacoes recentes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Alimento</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3 text-right">Quantidade</th>
                            <th class="px-6 py-3 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($movimentacoesRecentes as $movimentacao)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-600">{{ $movimentacao->data_movimentacao->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $movimentacao->alimento?->nome }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $movimentacao->tipo === 'entrada' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ucfirst($movimentacao->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-700">{{ number_format((float) $movimentacao->quantidade, 3, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-900">{{ number_format((float) $movimentacao->saldo_resultante, 3, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">Sem movimentacoes registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-secretaria-escolar-layout>
