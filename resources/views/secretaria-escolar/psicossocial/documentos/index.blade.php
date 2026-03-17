<x-secretaria-escolar-layout>
    <div class="space-y-6 px-8 py-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-700">Psicologia e Psicopedagogia</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Documentos tecnicos sigilosos</h1>
            <p class="mt-2 text-sm text-slate-500">Relatorios tecnicos, registros de atendimento e encaminhamentos com controle de acesso restrito.</p>
        </div>

        @include('documentos.partials.formularios', [
            'documentos' => $documentos,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'secretaria-escolar.psicossocial.documentos.preview',
        ])
    </div>
</x-secretaria-escolar-layout>
