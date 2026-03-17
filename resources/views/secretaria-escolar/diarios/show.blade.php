<x-secretaria-escolar-layout>
    @include('diarios.partials.painel', [
        'diario' => $diario,
        'matriculasAtivas' => $matriculasAtivas,
        'modoConsulta' => true,
    ])
</x-secretaria-escolar-layout>
