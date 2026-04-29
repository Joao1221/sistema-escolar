<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6 print:bg-white print:px-0 print:py-0 print:text-black">
        @include('partials.print-header', [
            'tituloPrint' => 'Cardapio diario',
            'subtituloPrint' => 'Data: ' . $cardapio->data_cardapio->format('d/m/Y') . ' | ' . ($cardapio->observacoes ?: 'Sem observacoes gerais.'),
            'escolaPrint' => $cardapio->escola,
        ])

        <div class="no-print flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-emerald-600">{{ $cardapio->escola?->nome }}</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Cardapio de {{ $cardapio->data_cardapio->format('d/m/Y') }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ $cardapio->observacoes ?: 'Sem observacoes gerais.' }}</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="window.print()" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Imprimir</button>
                <a href="{{ route('secretaria-escolar.alimentacao.cardapios.edit', $cardapio) }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Editar</a>
                <a href="{{ route('secretaria-escolar.alimentacao.cardapios.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Voltar</a>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3 print:grid-cols-1">
            @forelse ($cardapio->itens as $item)
                <div class="print-break-avoid rounded-3xl border border-slate-200 bg-white p-6 shadow-sm print:rounded-none print:shadow-none">
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600">{{ $item->refeicao }}</p>
                    <h2 class="mt-3 text-xl font-bold text-slate-900">{{ $item->alimento?->nome }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Quantidade prevista: {{ $item->quantidade_prevista !== null ? number_format((float) $item->quantidade_prevista, 3, ',', '.') : 'Nao informada' }}</p>
                    <p class="mt-3 text-sm text-slate-600">{{ $item->observacoes ?: 'Sem observacoes para este item.' }}</p>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                    Este cardapio ainda nao possui itens.
                </div>
            @endforelse
        </div>
    </div>
</x-secretaria-escolar-layout>
