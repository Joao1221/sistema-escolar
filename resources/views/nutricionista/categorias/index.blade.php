<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">{{ $categoriaEmEdicao ? 'Edicao' : 'Novo cadastro' }}</p>
                    <h2 class="mt-2 text-2xl font-bold text-[#17332a] font-fraunces">{{ $categoriaEmEdicao ? 'Editar categoria' : 'Nova categoria de alimento' }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Mantenha os grupos tecnicos usados no cadastro de alimentos da rede.</p>
                </div>
                @if ($categoriaEmEdicao)
                    <a href="{{ route('nutricionista.categorias.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancelar edicao
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ $categoriaEmEdicao ? route('nutricionista.categorias.update', $categoriaEmEdicao) : route('nutricionista.categorias.store') }}" class="mt-6 grid gap-5 lg:grid-cols-[1.1fr_1.4fr_auto]">
                @csrf
                @if ($categoriaEmEdicao)
                    @method('PUT')
                @endif
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nome</label>
                    <input type="text" name="nome" value="{{ old('nome', $categoriaEmEdicao?->nome) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Descricao</label>
                    <input type="text" name="descricao" value="{{ old('descricao', $categoriaEmEdicao?->descricao) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div class="flex items-end gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="ativo" value="1" @checked(old('ativo', $categoriaEmEdicao?->ativo ?? true)) class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                        Ativa
                    </label>
                    <button type="submit" class="rounded-2xl bg-[#17332a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#22473b]">
                        {{ $categoriaEmEdicao ? 'Salvar' : 'Cadastrar' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($categorias as $categoria)
                <article class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">{{ $categoria->ativo ? 'Ativa' : 'Inativa' }}</p>
                            <h2 class="mt-3 text-2xl font-bold text-[#17332a] font-fraunces">{{ $categoria->nome }}</h2>
                        </div>
                        <a href="{{ route('nutricionista.categorias.index', ['editar' => $categoria->id]) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Editar</a>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">{{ $categoria->descricao ?: 'Sem descricao cadastrada.' }}</p>
                    <div class="mt-5 inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        {{ $categoria->alimentos_count }} alimentos vinculados
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white/80 p-10 text-center text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                    Nenhuma categoria cadastrada ainda.
                </div>
            @endforelse
        </div>
    </div>
</x-nutricionista-layout>
