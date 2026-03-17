<x-nutricionista-layout titulo="Auditoria da Alimentacao" subtitulo="Eventos criticos da alimentacao escolar com foco tecnico e gerencial." :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        @include('auditoria.partials.filtros', ['rotaIndex' => route('nutricionista.auditoria.index')])
        @include('auditoria.partials.listagem')
    </div>
</x-nutricionista-layout>
