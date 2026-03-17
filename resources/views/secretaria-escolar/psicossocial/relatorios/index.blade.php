<x-secretaria-escolar-layout>
    <div class="space-y-6 px-8 py-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Psicologia e Psicopedagogia</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Relatorios tecnicos restritos</h1>
            <p class="mt-2 text-sm text-slate-500">Painel tecnico sigiloso com atendimentos, historico e producoes da equipe psicossocial.</p>
        </div>

        @include('relatorios.partials.formularios', [
            'relatorios' => $relatorios,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'secretaria-escolar.psicossocial.relatorios.preview',
        ])
    </div>
</x-secretaria-escolar-layout>
