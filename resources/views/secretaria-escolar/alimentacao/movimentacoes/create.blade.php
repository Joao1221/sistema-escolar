<x-secretaria-escolar-layout>
    <div class="px-8 py-6">
        <div class="mx-auto max-w-4xl rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Registrar {{ $tipoPadrao === 'saida' ? 'saida' : 'entrada' }}</h1>
                    <p class="mt-2 text-sm text-slate-500">Lance a movimentacao de estoque da escola com controle de saldo e validade.</p>
                </div>
                <a href="{{ route('secretaria-escolar.alimentacao.movimentacoes.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Voltar</a>
            </div>

            <form method="POST" action="{{ route('secretaria-escolar.alimentacao.movimentacoes.store') }}" class="mt-8 grid gap-5">
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
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Tipo</label>
                        <select name="tipo" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="entrada" @selected(old('tipo', $tipoPadrao) === 'entrada')>Entrada</option>
                            <option value="saida" @selected(old('tipo', $tipoPadrao) === 'saida')>Saida</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Alimento</label>
                        <select name="alimento_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Selecione</option>
                            @foreach ($alimentos as $alimento)
                                <option value="{{ $alimento->id }}" @selected(old('alimento_id') == $alimento->id)>{{ $alimento->nome }} - {{ $alimento->unidade_medida }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Fornecedor</label>
                        <select name="fornecedor_alimento_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Nao informar</option>
                            @foreach ($fornecedores as $fornecedor)
                                <option value="{{ $fornecedor->id }}" @selected(old('fornecedor_alimento_id') == $fornecedor->id)>{{ $fornecedor->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Quantidade</label>
                        <input type="number" step="0.001" min="0.001" name="quantidade" value="{{ old('quantidade') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Data</label>
                        <input type="date" name="data_movimentacao" value="{{ old('data_movimentacao', now()->toDateString()) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Validade</label>
                        <input type="date" name="data_validade" value="{{ old('data_validade') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Valor unitario</label>
                        <input type="number" step="0.01" min="0" name="valor_unitario" value="{{ old('valor_unitario') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Lote</label>
                        <input type="text" name="lote" value="{{ old('lote') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes</label>
                        <textarea name="observacoes" rows="3" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('observacoes') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Salvar movimentacao
                </button>
            </form>
        </div>
    </div>
</x-secretaria-escolar-layout>
