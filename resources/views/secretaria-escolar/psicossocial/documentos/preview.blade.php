<x-secretaria-escolar-layout>
    <div class="space-y-6 px-8 py-6">
        @include('documentos.partials.preview', [
            'documento' => $documento,
            'tipoDocumento' => $tipoDocumento,
            'payload' => $payload,
            'rotaImpressao' => 'secretaria-escolar.psicossocial.documentos.print',
        ])
    </div>
</x-secretaria-escolar-layout>
