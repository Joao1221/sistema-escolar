<x-secretaria-escolar-layout>
    <div class="space-y-6 px-8 py-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Coordenacao Pedagogica</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Consultas documentais pedagogicas</h1>
            <p class="mt-2 text-sm text-slate-500">Acesso apenas a documentos compativeis com o acompanhamento pedagogico do aluno.</p>
        </div>

        @include('documentos.partials.formularios', [
            'documentos' => $documentos,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'secretaria-escolar.coordenacao.documentos.preview',
        ])
    </div>
</x-secretaria-escolar-layout>
