<x-secretaria-escolar-layout>
    <div class="space-y-6 px-8 py-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Relatorios Escolares</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Relatorios administrativos e operacionais da escola</h1>
            <p class="mt-2 text-sm text-slate-500">Matriculas, frequencia, historico, ficha individual, AEE, turmas, alimentacao escolar e consulta de notas sem alteracao.</p>
        </div>

        @include('relatorios.partials.formularios', [
            'relatorios' => $relatorios,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'secretaria-escolar.relatorios.preview',
        ])
    </div>
</x-secretaria-escolar-layout>
