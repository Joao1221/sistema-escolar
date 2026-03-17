<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Planejamento alimentar</p>
                <h2 class="mt-2 text-2xl font-bold text-[#17332a] font-fraunces">Cardapios da rede</h2>
                <p class="mt-2 text-sm text-slate-500">Consulte, compare e registre cardapios por escola sem sair do portal tecnico.</p>
            </div>
            <a href="{{ route('nutricionista.cardapios.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-[#17332a] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#22473b]">
                Novo cardapio
            </a>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm">
            <form method="GET" action="{{ route('nutricionista.cardapios.index') }}" class="grid gap-4 md:grid-cols-3">
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
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Data</label>
                    <input type="date" name="data_cardapio" value="{{ $filtros['data_cardapio'] ?? null }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="w-full rounded-xl bg-[#17332a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#22473b]">Filtrar</button>
                    <a href="{{ route('nutricionista.cardapios.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            @forelse ($cardapios as $cardapio)
                <article class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">{{ $cardapio->escola?->nome }}</p>
                            <h2 class="mt-3 text-2xl font-bold text-[#17332a] font-fraunces">{{ $cardapio->data_cardapio->format('d/m/Y') }}</h2>
                            <p class="mt-2 text-sm text-slate-600">{{ $cardapio->observacoes ?: 'Sem observacoes gerais.' }}</p>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $cardapio->itens->count() }} itens</span>
                    </div>
                    <div class="mt-5 space-y-2 text-sm text-slate-600">
                        @foreach ($cardapio->itens->take(4) as $item)
                            <p><strong>{{ $item->refeicao }}:</strong> {{ $item->alimento?->nome }}</p>
                        @endforeach
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('nutricionista.cardapios.show', $cardapio) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Visualizar</a>
                        <a href="{{ route('nutricionista.cardapios.edit', $cardapio) }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">Editar</a>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white/80 p-10 text-center text-sm text-slate-500 lg:col-span-2">
                    Nenhum cardapio encontrado.
                </div>
            @endforelse
        </div>

        @if ($cardapios->hasPages())
            {{ $cardapios->links() }}
        @endif
    </div>
</x-nutricionista-layout>
