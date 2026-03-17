<x-secretaria-layout>
    <div class="space-y-6 px-8 py-6">
        @include('relatorios.partials.preview', [
            'relatorio' => $relatorio,
            'tipoRelatorio' => $tipoRelatorio,
            'payload' => $payload,
            'rotaImpressao' => 'secretaria.relatorios.print',
        ])
    </div>
</x-secretaria-layout>
