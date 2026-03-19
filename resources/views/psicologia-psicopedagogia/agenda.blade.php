<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Fluxo de trabalho</p>
                <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Agenda de atendimentos</h1>
                <p class="mt-2 text-sm text-slate-500">Somente compromissos agendados no fluxo tecnico sigiloso.</p>
            </div>
            <a href="{{ route('psicologia.create') }}" class="inline-flex items-center rounded-xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-black/20 transition hover:bg-slate-900">+ Novo atendimento</a>
        </div>

        @include('psicologia-psicopedagogia.partials.listagem', [
            'rota' => route('psicologia.agenda'),
            'rotaShow' => 'psicologia.show',
            'atendimentos' => $atendimentos,
            'escolas' => $escolas,
            'filtros' => $filtros,
            'statusOptions' => ['agendado'],
        ])
    </div>
</x-psicologia-layout>
