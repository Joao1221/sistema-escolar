@php
    $instituicao = $relatorio['instituicao'];
    $escola = $relatorio['escola'];
@endphp

<div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm print:rounded-none print:border-0 print:shadow-none print:p-0">
    <div class="flex flex-wrap items-start justify-between gap-6 border-b border-slate-200 pb-6">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-3">
                @if (! empty($instituicao['brasao_url']))
                    <img src="{{ $instituicao['brasao_url'] }}" alt="Brasao da prefeitura" class="h-16 w-16 rounded-2xl object-cover">
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">{{ $instituicao['nome_prefeitura'] ?: 'Prefeitura Municipal' }}</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $instituicao['nome_secretaria'] ?: 'Secretaria de Educacao' }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ trim(($instituicao['municipio'] ?: '') . ' / ' . ($instituicao['uf'] ?: '')) }}</p>
            </div>
        </div>

        <div class="text-right">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Codigo</p>
            <p class="mt-2 text-lg font-bold text-slate-900">{{ $relatorio['codigo'] }}</p>
            <p class="mt-1 text-sm text-slate-500">Emitido em {{ $relatorio['emitido_em']->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h1 class="text-3xl font-bold text-slate-900">{{ $relatorio['titulo'] }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ $relatorio['subtitulo'] }}</p>
        @if ($escola)
            <p class="mt-2 text-sm text-slate-500">{{ $escola['nome'] }} | {{ $escola['endereco'] }}</p>
        @endif
    </div>

    @if (! empty($relatorio['filtros_aplicados']))
        <section class="mt-8">
            <h3 class="text-lg font-bold text-slate-900">Filtros aplicados</h3>
            <div class="mt-3 grid gap-3 md:grid-cols-2">
                @foreach ($relatorio['filtros_aplicados'] as $label => $valor)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $label }}</p>
                        <p class="mt-2 text-sm text-slate-800">{{ $valor }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-8">
        <h3 class="text-lg font-bold text-slate-900">Indicadores</h3>
        <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($relatorio['metricas'] as $label => $valor)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $valor }}</p>
                </div>
            @endforeach
        </div>
    </section>

    @foreach ($relatorio['secoes'] as $secao)
        <section class="mt-8">
            <h3 class="text-lg font-bold text-slate-900">{{ $secao['titulo'] }}</h3>

            @if ($secao['tipo'] === 'lista')
                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    @foreach ($secao['itens'] as $item)
                        <div class="rounded-2xl border border-slate-200 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $item['label'] }}</p>
                            <p class="mt-2 text-sm text-slate-800">{{ $item['valor'] }}</p>
                        </div>
                    @endforeach
                </div>
            @elseif ($secao['tipo'] === 'tabela')
                <div class="mt-3 overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            <tr>
                                @foreach ($secao['colunas'] as $coluna)
                                    <th class="px-4 py-3">{{ $coluna }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($secao['linhas'] as $linha)
                                <tr>
                                    @foreach ($linha as $celula)
                                        <td class="px-4 py-3 text-slate-700">{{ $celula }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($secao['colunas']) }}" class="px-4 py-6 text-center text-slate-500">Sem registros para exibir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    @endforeach
</div>
