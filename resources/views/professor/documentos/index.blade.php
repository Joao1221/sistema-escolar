<x-professor-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    @include('documentos.partials.formularios', [
        'documentos' => $documentos,
        'opcoesFormulario' => $opcoesFormulario,
        'rotaPreview' => 'professor.documentos.preview',
    ])
</x-professor-layout>
