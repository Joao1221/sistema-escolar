<x-secretaria-layout>
    <div class="space-y-6 px-8 py-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-indigo-600">Relatorios da Rede</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Relatorios consolidados da Secretaria</h1>
            <p class="mt-2 text-sm text-slate-500">Panorama institucional, matriculas, AEE, turmas, professores e auditoria em nivel de rede.</p>
        </div>

        @include('relatorios.partials.formularios', [
            'relatorios' => $relatorios,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'secretaria.relatorios.preview',
        ])
    </div>
</x-secretaria-layout>
