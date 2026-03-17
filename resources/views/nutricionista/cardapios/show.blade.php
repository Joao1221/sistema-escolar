<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">{{ $cardapio->escola?->nome }}</p>
                <h2 class="mt-2 text-2xl font-bold text-[#17332a] font-fraunces">{{ $cardapio->data_cardapio->format('d/m/Y') }}</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $cardapio->observacoes ?: 'Sem observacoes gerais.' }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('nutricionista.cardapios.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Voltar</a>
                <a href="{{ route('nutricionista.cardapios.edit', $cardapio) }}" class="rounded-xl bg-[#17332a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#22473b]">Editar</a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h3 class="text-lg font-bold text-[#17332a] font-fraunces">Itens do cardapio</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach ($cardapio->itens as $item)
                    <div class="px-6 py-4">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-700">{{ $item->refeicao }}</p>
                                <p class="mt-1 text-lg font-semibold text-slate-900">{{ $item->alimento?->nome }}</p>
                            </div>
                            <div class="text-sm text-slate-600">
                                Qtd. prevista: {{ $item->quantidade_prevista !== null ? number_format((float) $item->quantidade_prevista, 3, ',', '.') : 'Nao informada' }}
                            </div>
                        </div>
                        @if ($item->observacoes)
                            <p class="mt-3 text-sm text-slate-500">{{ $item->observacoes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-nutricionista-layout>
