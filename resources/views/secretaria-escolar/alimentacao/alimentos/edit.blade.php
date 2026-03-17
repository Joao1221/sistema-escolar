<x-secretaria-escolar-layout>
    <div class="px-8 py-6">
        <div class="mx-auto max-w-3xl rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Editar alimento</h1>
                    <p class="mt-2 text-sm text-slate-500">Atualize os dados operacionais deste item.</p>
                </div>
                <a href="{{ route('secretaria-escolar.alimentacao.alimentos.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Voltar</a>
            </div>

            <form method="POST" action="{{ route('secretaria-escolar.alimentacao.alimentos.update', $alimento) }}" class="mt-8 grid gap-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Categoria</label>
                    <select name="categoria_alimento_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('categoria_alimento_id', $alimento->categoria_alimento_id) == $categoria->id)>{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Nome</label>
                        <input type="text" name="nome" value="{{ old('nome', $alimento->nome) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Unidade de medida</label>
                        <input type="text" name="unidade_medida" value="{{ old('unidade_medida', $alimento->unidade_medida) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Estoque minimo</label>
                        <input type="number" step="0.001" min="0" name="estoque_minimo" value="{{ old('estoque_minimo', $alimento->estoque_minimo) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                            <input type="checkbox" name="controla_validade" value="1" @checked(old('controla_validade', $alimento->controla_validade)) class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                            Controla validade
                        </label>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes</label>
                    <textarea name="observacoes" rows="4" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('observacoes', $alimento->observacoes) }}</textarea>
                </div>
                <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                    <input type="checkbox" name="ativo" value="1" @checked(old('ativo', $alimento->ativo)) class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                    Alimento ativo
                </label>
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Salvar alteracoes
                </button>
            </form>
        </div>
    </div>
</x-secretaria-escolar-layout>
