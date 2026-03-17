<x-secretaria-escolar-layout>
    <div class="space-y-6 px-8 py-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Direcao Escolar</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ $configuracaoPortal['titulo'] }}</h1>
            <p class="mt-2 text-sm text-slate-500">{{ $configuracaoPortal['descricao'] }}</p>
        </div>

        @include('auditoria.partials.filtros', ['rotaIndex' => route('secretaria-escolar.direcao.auditoria.index')])
        @include('auditoria.partials.listagem')
    </div>
</x-secretaria-escolar-layout>
