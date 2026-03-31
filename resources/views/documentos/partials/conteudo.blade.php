@php
    $instituicao = $documento['instituicao'];
    $escola = $documento['escola'];
    $quantidadeDadosChave = count($documento['dados_chave'] ?? []);
    $ehRelatorioTecnico = ($documento['layout'] ?? null) === 'relatorio-tecnico';
    $assinaturas = collect($documento['assinaturas_personalizadas'] ?? [])
        ->filter()
        ->values()
        ->all();

    if (empty($assinaturas)) {
        $assinaturas = $instituicao['assinaturas'] ?? [];
    }
@endphp

<div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm print:rounded-none print:border-0 print:shadow-none print:p-0">
    <div class="flex flex-wrap items-start justify-between gap-6 border-b border-slate-200 pb-6">
        <div class="flex flex-1 items-start gap-4">
            <div class="flex items-center gap-3">
                @if (! empty($instituicao['brasao_url']))
                    <img src="{{ $instituicao['brasao_url'] }}" alt="Brasao da prefeitura" class="h-16 w-16 rounded-2xl object-cover">
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">{{ $instituicao['nome_prefeitura'] ?: 'Prefeitura Municipal' }}</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 print:text-lg">{{ $instituicao['nome_secretaria'] ?: 'Secretaria de Educacao' }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ trim(($instituicao['municipio'] ?: '') . ' / ' . ($instituicao['uf'] ?: '')) }}</p>
            </div>
        </div>

        @unless ($ehRelatorioTecnico)
            <div class="w-full max-w-md">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">C&oacute;digo</p>
                <div class="mt-1 flex flex-col items-end justify-between gap-1">
                    <p class="text-base font-bold text-slate-900">{{ $documento['codigo'] }}</p>
                    <p class="text-xs text-right text-slate-500">Emitido em {{ $documento['emitido_em']->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        @endunless
    </div>

    @if ($ehRelatorioTecnico)
        <div class="mt-3 flex items-start justify-between gap-8 border-b border-slate-200 pb-6">
            <div class="space-y-0.5">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Contato institucional</p>
                <p class="text-xs leading-4 text-slate-700">{{ $instituicao['telefone'] ?: 'Telefone nao informado' }}</p>
                <p class="text-xs leading-4 text-slate-700">{{ $instituicao['email'] ?: 'Email nao informado' }}</p>
            </div>
            <div class="min-w-[220px] text-right">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">C&oacute;digo</p>
                <p class="mt-1 text-base font-bold text-slate-900">{{ $documento['codigo'] }}</p>
                <p class="mt-1 text-xs text-slate-500">Emitido em {{ $documento['emitido_em']->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    @endif

    <div class="mt-8">
        <h1 class="text-2xl font-bold text-slate-900 print:text-xl">{{ $documento['titulo'] }}</h1>
        @if ($escola)
            <p class="mt-1 text-xs text-slate-500">{{ $escola['nome'] }} | {{ $escola['endereco'] }}</p>
        @endif
    </div>

    @if ($ehRelatorioTecnico && $quantidadeDadosChave === 4)
        <div class="mt-8 flex flex-row gap-3 print:flex-row">
            @foreach ($documento['dados_chave'] as $label => $valor)
                <div class="min-w-0 flex-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $valor }}</p>
                </div>
            @endforeach
        </div>
    @elseif ($ehRelatorioTecnico && $quantidadeDadosChave === 3)
        <div class="mt-8 grid grid-cols-3 gap-3 print:grid-cols-3">
            @foreach ($documento['dados_chave'] as $label => $valor)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $valor }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div @class([
            'mt-8 grid grid-cols-1 gap-4',
            'md:grid-cols-2 xl:grid-cols-4 print:grid-cols-4' => $quantidadeDadosChave >= 4,
            'md:grid-cols-2 print:grid-cols-2' => $quantidadeDadosChave === 2,
            'md:grid-cols-1 print:grid-cols-1' => $quantidadeDadosChave <= 1,
        ])>
            @foreach ($documento['dados_chave'] as $label => $valor)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $valor }}</p>
                </div>
            @endforeach
        </div>
    @endif

    @if (! empty($documento['paragrafos']))
        <div class="mt-8 space-y-4 text-justify text-sm leading-7 text-slate-700">
            @foreach ($documento['paragrafos'] as $paragrafo)
                <p>{{ $paragrafo }}</p>
            @endforeach
        </div>
    @endif

    @foreach ($documento['secoes'] as $secao)
        <section class="mt-8">
            <h3 class="text-lg font-bold text-slate-900">{{ $secao['titulo'] }}</h3>

            @if ($secao['tipo'] === 'texto')
                <div class="mt-3 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm leading-7 text-slate-700 whitespace-pre-line">{{ $secao['conteudo'] }}</div>
            @elseif ($secao['tipo'] === 'lista')
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

    <div class="mt-10 grid gap-4 md:grid-cols-2">
        @unless ($ehRelatorioTecnico)
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Contato institucional</p>
                <p class="mt-2 text-sm text-slate-700">{{ $instituicao['telefone'] ?: 'Telefone nao informado' }}</p>
                <p class="mt-1 text-sm text-slate-700">{{ $instituicao['email'] ?: 'Email nao informado' }}</p>
                @if ($instituicao['texto'])
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $instituicao['texto'] }}</p>
                @endif
            </div>
        @endunless

        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 {{ $ehRelatorioTecnico ? 'md:col-span-2' : '' }}">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Assinaturas e cargos</p>
            <div class="mt-4 space-y-4">
                @foreach ($assinaturas as $assinatura)
                    <div class="border-t border-dashed border-slate-300 pt-4 text-sm text-slate-700">{{ $assinatura }}</div>
                @endforeach
            </div>
        </div>
    </div>
</div>
