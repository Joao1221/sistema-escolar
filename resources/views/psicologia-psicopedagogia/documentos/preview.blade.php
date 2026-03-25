<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        @if (! empty($acoesDocumento ?? []))
            <div class="flex flex-wrap justify-end gap-3">
                @foreach ($acoesDocumento as $acao)
                    @if (($acao['tipo'] ?? 'link') === 'form')
                        <form method="POST" action="{{ $acao['url'] }}" @if (! empty($acao['confirmacao'])) onsubmit="return confirm('{{ $acao['confirmacao'] }}');" @endif>
                            @csrf
                            @method($acao['metodo'] ?? 'POST')
                            <button type="submit" title="{{ $acao['label'] }}" aria-label="{{ $acao['label'] }}" class="inline-flex items-center rounded-2xl px-4 py-2 text-sm font-semibold shadow-sm transition {{ ($acao['estilo'] ?? null) === 'perigo' ? 'border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100' }}">
                                @if (($acao['estilo'] ?? null) === 'perigo')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.5 2a1 1 0 0 0-.894.553L7.382 3H5a1 1 0 1 0 0 2h.293l.842 10.112A2 2 0 0 0 8.128 17h3.744a2 2 0 0 0 1.993-1.888L14.707 5H15a1 1 0 1 0 0-2h-2.382l-.224-.447A1 1 0 0 0 11.5 2h-3Zm1 5a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1Zm3 1a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="sr-only">{{ $acao['label'] }}</span>
                                @else
                                    {{ $acao['label'] }}
                                @endif
                            </button>
                        </form>
                    @else
                        <a href="{{ $acao['url'] }}" class="inline-flex items-center rounded-2xl px-4 py-2 text-sm font-semibold shadow-sm transition {{ ($acao['estilo'] ?? null) === 'secundario' ? 'border border-cyan-200 bg-cyan-50 text-cyan-800 hover:bg-cyan-100' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100' }}">
                            {{ $acao['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

        @include('documentos.partials.preview', [
            'documento' => $documento,
            'tipoDocumento' => $tipoDocumento,
            'payload' => $payload,
            'rotaImpressao' => 'psicologia.documentos.print',
        ])
    </div>
</x-psicologia-layout>
