<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Historico de atendimentos</h1>
            <p class="mt-2 text-sm text-slate-500">Consulta sigilosa dos registros realizados no modulo.</p>
        </div>
        @include('secretaria-escolar.psicossocial.partials.listagem', ['rota' => route('secretaria-escolar.psicossocial.historico'), 'atendimentos' => $atendimentos, 'escolas' => $escolas, 'filtros' => $filtros])
    </div>
</x-secretaria-escolar-layout>
