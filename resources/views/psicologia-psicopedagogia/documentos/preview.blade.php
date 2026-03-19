<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        @include('documentos.partials.preview', [
            'documento' => $documento,
            'tipoDocumento' => $tipoDocumento,
            'payload' => $payload,
            'rotaImpressao' => 'psicologia.documentos.print',
        ])
    </div>
</x-psicologia-layout>
