<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        @include('relatorios.partials.preview', [
            'relatorio' => $relatorio,
            'tipoRelatorio' => $tipoRelatorio,
            'payload' => $payload,
            'rotaImpressao' => 'psicologia.relatorios_tecnicos.print',
        ])
    </div>
</x-psicologia-layout>
