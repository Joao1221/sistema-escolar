@php
    $instituicao = $documento['instituicao'];
    $escola = $documento['escola'];
@endphp

<div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm print:rounded-none print:border-0 print:shadow-none print:p-0">
    <div class="flex flex-wrap items-start justify-between gap-6 border-b border-slate-200 pb-6">
        <div class="flex items-center gap-4">
            @if ($instituicao['brasao_url'])
                <img src="{{ $instituicao['brasao_url'] }}" alt="Brasao" class="h-16 w-16 rounded-2xl object-cover">
            @endif
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">{{ $instituicao['nome_prefeitura'] ?: 'Prefeitura Municipal' }}</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $instituicao['nome_secretaria'] ?: 'Secretaria de Educacao' }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ trim(($instituicao['municipio'] ?: '') . ' / ' . ($instituicao['uf'] ?: '')) }}</p>
            </div>
        </div>

        <div class="text-right">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Codigo</p>
            <p class="mt-2 text-lg font-bold text-slate-900">{{ $documento['codigo'] }}</p>
            <p class="mt-1 text-sm text-slate-500">Emitido em {{ $documento['emitido_em']->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h1 class="text-3xl font-bold text-slate-900">{{ $documento['titulo'] }}</h1>
        @if ($escola)
            <p class="mt-2 text-sm text-slate-500">{{ $escola['nome'] }} | {{ $escola['endereco'] }}</p>
        @endif
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-2">
        @foreach ($documento['dados_chave'] as $label => $valor)
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $valor }}</p>
            </div>
        @endforeach
    </div>

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
        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Contato institucional</p>
            <p class="mt-2 text-sm text-slate-700">{{ $instituicao['telefone'] ?: 'Telefone nao informado' }}</p>
            <p class="mt-1 text-sm text-slate-700">{{ $instituicao['email'] ?: 'Email nao informado' }}</p>
            @if ($instituicao['texto'])
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $instituicao['texto'] }}</p>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Assinaturas e cargos</p>
            <div class="mt-4 space-y-4">
                @forelse ($instituicao['assinaturas'] as $assinatura)
                    <div class="border-t border-dashed border-slate-300 pt-4 text-sm text-slate-700">{{ $assinatura }}</div>
                @empty
                    <div class="border-t border-dashed border-slate-300 pt-4 text-sm text-slate-500">Nenhuma assinatura configurada.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
