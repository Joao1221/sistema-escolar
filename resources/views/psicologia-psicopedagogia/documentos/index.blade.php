<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Sigilo documental</p>
            <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Documentos restritos</h1>
            <p class="mt-2 text-sm text-slate-500">Registros de atendimento, relatorios e encaminhamentos com fluxo de impressao protegido.</p>
        </div>

        @include('documentos.partials.formularios', [
            'documentos' => $documentos,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'psicologia.documentos.preview',
        ])
    </div>
</x-psicologia-layout>
