<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="mx-auto max-w-5xl rounded-3xl border border-slate-200 bg-white/90 p-8 shadow-sm">
        <form method="POST" action="{{ route('nutricionista.cardapios.store') }}" class="space-y-6">
            @csrf
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Escola</label>
                    <select name="escola_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" @selected(old('escola_id', $escolas->count() === 1 ? $escolas->first()->id : null) == $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Data do cardapio</label>
                    <input type="date" name="data_cardapio" value="{{ old('data_cardapio', now()->toDateString()) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes gerais</label>
                <textarea name="observacoes" rows="3" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('observacoes') }}</textarea>
            </div>

            <div class="rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Itens do cardapio</h2>
                    <button type="button" id="adicionar-item" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Adicionar item</button>
                </div>
                <div id="itens-cardapio" class="space-y-4 p-5">
                    @php($itensOld = old('itens', [['refeicao' => 'cafe_da_manha']]))
                    @foreach ($itensOld as $indice => $item)
                        <div class="grid gap-4 rounded-2xl border border-slate-200 p-4 md:grid-cols-[1fr_1fr_140px_auto]">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Refeicao</label>
                                <input type="text" name="itens[{{ $indice }}][refeicao]" value="{{ $item['refeicao'] ?? '' }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Alimento</label>
                                <select name="itens[{{ $indice }}][alimento_id]" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Selecione</option>
                                    @foreach ($alimentos as $alimento)
                                        <option value="{{ $alimento->id }}" @selected(($item['alimento_id'] ?? null) == $alimento->id)>{{ $alimento->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Qtd. prevista</label>
                                <input type="number" step="0.001" min="0" name="itens[{{ $indice }}][quantidade_prevista]" value="{{ $item['quantidade_prevista'] ?? '' }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="remover-item rounded-xl border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">Remover</button>
                            </div>
                            <div class="md:col-span-4">
                                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes do item</label>
                                <input type="text" name="itens[{{ $indice }}][observacoes]" value="{{ $item['observacoes'] ?? '' }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('nutricionista.cardapios.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</a>
                <button type="submit" class="rounded-xl bg-[#17332a] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#22473b]">
                    Salvar cardapio
                </button>
            </div>
        </form>
    </div>

    @include('nutricionista.cardapios.partials.script-itens')
</x-nutricionista-layout>
