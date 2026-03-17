<x-professor-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    @include('documentos.partials.preview', [
        'documento' => $documento,
        'tipoDocumento' => $tipoDocumento,
        'payload' => $payload,
        'rotaImpressao' => 'professor.documentos.print',
    ])
</x-professor-layout>
