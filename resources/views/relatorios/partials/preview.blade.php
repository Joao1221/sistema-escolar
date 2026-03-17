<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Visualizacao previa</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ $relatorio['titulo'] }}</h1>
        </div>

        <form method="POST" action="{{ route($rotaImpressao, $tipoRelatorio) }}" target="_blank">
            @csrf
            @foreach ($payload as $chave => $valor)
                <input type="hidden" name="{{ $chave }}" value="{{ $valor }}">
            @endforeach
            <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                Abrir versao para impressao
            </button>
        </form>
    </div>

    @include('relatorios.partials.conteudo', ['relatorio' => $relatorio])
</div>
