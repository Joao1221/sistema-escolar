<x-professor-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    @include('diarios.partials.painel', [
        'diario' => $diario,
        'matriculasAtivas' => $matriculasAtivas,
        'horariosRelacionados' => $horariosRelacionados,
        'modoConsulta' => false,
    ])
</x-professor-layout>
