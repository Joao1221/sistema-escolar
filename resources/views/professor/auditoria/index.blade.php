<x-professor-layout titulo="Minha Auditoria" subtitulo="Rastros do proprio trabalho docente e devolutivas relacionadas." :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        @include('auditoria.partials.filtros', ['rotaIndex' => route('professor.auditoria.index')])
        @include('auditoria.partials.listagem')
    </div>
</x-professor-layout>
