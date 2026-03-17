<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="mx-auto max-w-3xl rounded-3xl border border-slate-200 bg-white/90 p-8 shadow-sm">
        <form method="POST" action="{{ route('nutricionista.alimentos.store') }}" class="grid gap-5">
            @csrf
            <div>
                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Categoria</label>
                <select name="categoria_alimento_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Selecione</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @selected(old('categoria_alimento_id') == $categoria->id)>{{ $categoria->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Nome</label>
                    <input type="text" name="nome" value="{{ old('nome') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Unidade de medida</label>
                    <input type="text" name="unidade_medida" value="{{ old('unidade_medida', 'kg') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Estoque minimo</label>
                    <input type="number" step="0.001" min="0" name="estoque_minimo" value="{{ old('estoque_minimo', 0) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div class="flex items-end">
                    <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                        <input type="checkbox" name="controla_validade" value="1" @checked(old('controla_validade', true)) class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                        Controla validade
                    </label>
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes</label>
                <textarea name="observacoes" rows="4" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('observacoes') }}</textarea>
            </div>
            <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                <input type="checkbox" name="ativo" value="1" @checked(old('ativo', true)) class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                Alimento ativo
            </label>
            <div class="flex justify-end gap-3">
                <a href="{{ route('nutricionista.alimentos.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</a>
                <button type="submit" class="rounded-xl bg-[#17332a] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#22473b]">
                    Cadastrar alimento
                </button>
            </div>
        </form>
    </div>
</x-nutricionista-layout>
