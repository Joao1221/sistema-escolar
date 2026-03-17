<x-nutricionista-layout titulo="Relatorios da Alimentacao" subtitulo="Comparativos gerenciais, estoque, validade, cardapios e consumo entre escolas." :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="rounded-3xl border border-emerald-100 bg-gradient-to-r from-emerald-50 to-amber-50 px-6 py-5 text-sm text-slate-700 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Comparativo gerencial por escola</p>
            <p class="mt-2">Selecione o relatorio desejado para analisar cardapios, estoque, validade, entradas, saidas e consumo consolidado entre unidades.</p>
        </div>

        @include('relatorios.partials.formularios', [
            'relatorios' => $relatorios,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'nutricionista.relatorios.preview',
        ])
    </div>
</x-nutricionista-layout>
