<x-secretaria-escolar-layout>
    <div class="space-y-6 px-8 py-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Documentos Escolares</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Emissao operacional da escola</h1>
            <p class="mt-2 text-sm text-slate-500">Guia de transferencia, declaracoes, fichas, historico e documentos administrativos no contexto da escola.</p>
        </div>

        @include('documentos.partials.formularios', [
            'documentos' => $documentos,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'secretaria-escolar.documentos.preview',
        ])
    </div>
</x-secretaria-escolar-layout>
