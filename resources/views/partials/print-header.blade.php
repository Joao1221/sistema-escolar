@php
    $instituicaoPrint = $instituicaoPrint ?? \App\Models\Instituicao::query()->first();
    $tituloPrint = $tituloPrint ?? 'Documento';
    $subtituloPrint = $subtituloPrint ?? null;
    $escolaPrint = $escolaPrint ?? null;
    $escolaPrintNome = is_string($escolaPrint) ? $escolaPrint : data_get($escolaPrint, 'nome');
    $escolaPrintEndereco = is_string($escolaPrint) ? null : data_get($escolaPrint, 'endereco');
@endphp

<section class="print-only mb-6 border-b border-slate-300 pb-4 text-black">
    <div class="flex items-start gap-4">
        @if ($instituicaoPrint?->brasao_url)
            <img src="{{ $instituicaoPrint->brasao_url }}" alt="Brasao da prefeitura" class="h-16 w-16 object-contain">
        @endif

        <div class="min-w-0 flex-1">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-700">
                {{ $instituicaoPrint?->nome_prefeitura ?: 'Prefeitura Municipal' }}
            </p>
            <h2 class="mt-1 text-lg font-bold text-black">
                {{ $instituicaoPrint?->nome_secretaria ?: 'Secretaria de Educacao' }}
            </h2>
            <p class="mt-1 text-xs text-slate-700">
                {{ trim(($instituicaoPrint?->municipio ?: '') . ' / ' . ($instituicaoPrint?->uf ?: '')) }}
            </p>

            @if ($escolaPrintNome)
                <p class="mt-2 text-sm font-semibold text-black">{{ $escolaPrintNome }}</p>
                @if ($escolaPrintEndereco)
                    <p class="text-xs text-slate-700">{{ $escolaPrintEndereco }}</p>
                @endif
            @endif
        </div>

        <div class="text-right text-xs text-slate-700">
            <p>Gerado em</p>
            <p class="font-semibold text-black">{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="mt-5">
        <h1 class="text-xl font-bold uppercase text-black">{{ $tituloPrint }}</h1>
        @if ($subtituloPrint)
            <p class="mt-1 text-sm text-slate-700">{{ $subtituloPrint }}</p>
        @endif
    </div>
</section>
