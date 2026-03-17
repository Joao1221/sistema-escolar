<x-secretaria-escolar-layout>
    <div class="space-y-6 px-8 py-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-indigo-600">Direcao Escolar</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Documentos gerenciais da escola</h1>
            <p class="mt-2 text-sm text-slate-500">Atas, oficios e declaracoes emitidos no escopo da direcao escolar.</p>
        </div>

        @include('documentos.partials.formularios', [
            'documentos' => $documentos,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'secretaria-escolar.direcao.documentos.preview',
        ])
    </div>
</x-secretaria-escolar-layout>
