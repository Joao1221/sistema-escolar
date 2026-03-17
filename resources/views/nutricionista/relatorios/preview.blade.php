<x-nutricionista-layout titulo="Visualizacao de Relatorio" subtitulo="Conferencia previa antes da impressao ou exportacao." :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        @include('relatorios.partials.preview', [
            'relatorio' => $relatorio,
            'tipoRelatorio' => $tipoRelatorio,
            'payload' => $payload,
            'rotaImpressao' => 'nutricionista.relatorios.print',
        ])
    </div>
</x-nutricionista-layout>
