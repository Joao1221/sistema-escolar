<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Rastros sensiveis</p>
            <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Auditoria restrita</h1>
            <p class="mt-2 text-sm text-slate-500">{{ $configuracaoPortal['descricao'] ?? 'Auditoria tecnica altamente restrita.' }}</p>
        </div>

        @include('auditoria.partials.filtros', ['rotaIndex' => route('psicologia.auditoria.index')])
        @include('auditoria.partials.listagem')
    </div>
</x-psicologia-layout>
