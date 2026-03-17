<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Cardapio diario</h1>
                <p class="mt-2 text-sm text-slate-500">Monte e acompanhe o cardapio por data no contexto da escola.</p>
            </div>
            <a href="{{ route('secretaria-escolar.alimentacao.cardapios.create') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Novo cardapio</a>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('secretaria-escolar.alimentacao.cardapios.index') }}" class="grid gap-4 md:grid-cols-3">
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
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Data do cardapio</label>
                    <input type="date" name="data_cardapio" value="{{ $filtros['data_cardapio'] ?? null }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filtrar</button>
                    <a href="{{ route('secretaria-escolar.alimentacao.cardapios.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            @forelse ($cardapios as $cardapio)
                <a href="{{ route('secretaria-escolar.alimentacao.cardapios.show', $cardapio) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-widest text-emerald-600">{{ $cardapio->escola?->nome }}</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $cardapio->data_cardapio->format('d/m/Y') }}</h2>
                            <p class="mt-2 text-sm text-slate-500">{{ $cardapio->observacoes ?: 'Sem observacoes registradas.' }}</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $cardapio->itens->count() }} itens</span>
                    </div>
                </a>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500 lg:col-span-2">
                    Nenhum cardapio encontrado para os filtros informados.
                </div>
            @endforelse
        </div>

        @if ($cardapios->hasPages())
            <div>
                {{ $cardapios->links() }}
            </div>
        @endif
    </div>
</x-secretaria-escolar-layout>
