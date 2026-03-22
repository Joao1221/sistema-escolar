<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Consulta sigilosa</p>
            <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Historico de atendimentos</h1>
            <p class="mt-2 text-sm text-slate-500">Atendimentos realizados, cancelados ou sem comparecimento, conforme o filtro aplicado.</p>
        </div>

        @include('psicologia-psicopedagogia.partials.listagem', [
            'rota' => route('psicologia.historico.index'),
            'rotaShow' => 'psicologia.show',
            'atendimentos' => $atendimentos,
            'escolas' => $escolas,
            'filtros' => $filtros,
            'statusOptions' => ['realizado', 'cancelado', 'faltou', 'encerrado'],
        ])
    </div>
</x-psicologia-layout>
