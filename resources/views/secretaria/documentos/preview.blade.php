<x-secretaria-layout>
    <div class="space-y-6">
        @include('documentos.partials.preview', [
            'documento' => $documento,
            'tipoDocumento' => $tipoDocumento,
            'payload' => $payload,
            'rotaImpressao' => 'secretaria.documentos.print',
        ])
    </div>
</x-secretaria-layout>
