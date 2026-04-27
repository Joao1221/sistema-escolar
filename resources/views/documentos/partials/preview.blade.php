<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Visualizacao previa</p>
            <h1 class="mt-2 text-3xl font-bold {{ ($theme ?? auth()->user()?->theme ?? 'lilas') === 'lilas' ? 'text-slate-900' : 'text-gray-200' }}">{{ $documento['titulo'] }}</h1>
        </div>

        @if (! empty($urlImpressaoDireta ?? null))
            <a href="{{ $urlImpressaoDireta }}" target="_blank" class="inline-flex items-center rounded-2xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">
                Abrir versao para impressao
            </a>
        @else
            <form method="POST" action="{{ route($rotaImpressao, $tipoDocumento) }}" target="_blank">
                @csrf
                @foreach ($payload as $chave => $valor)
                    <input type="hidden" name="{{ $chave }}" value="{{ $valor }}">
                @endforeach
                <button type="submit" class="inline-flex items-center rounded-2xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">
                    Abrir versao para impressao
                </button>
            </form>
        @endif
    </div>

    @include('documentos.partials.conteudo', ['documento' => $documento])
</div>
