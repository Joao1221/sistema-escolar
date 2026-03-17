<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">{{ $fornecedorEmEdicao ? 'Edicao' : 'Novo cadastro' }}</p>
                    <h2 class="mt-2 text-2xl font-bold text-[#17332a] font-fraunces">{{ $fornecedorEmEdicao ? 'Editar fornecedor' : 'Novo fornecedor' }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Gerencie os fornecedores homologados para a alimentacao escolar.</p>
                </div>
                @if ($fornecedorEmEdicao)
                    <a href="{{ route('nutricionista.fornecedores.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancelar edicao
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ $fornecedorEmEdicao ? route('nutricionista.fornecedores.update', $fornecedorEmEdicao) : route('nutricionista.fornecedores.store') }}" class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @csrf
                @if ($fornecedorEmEdicao)
                    @method('PUT')
                @endif
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nome</label>
                    <input type="text" name="nome" value="{{ old('nome', $fornecedorEmEdicao?->nome) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">CNPJ</label>
                    <input type="text" name="cnpj" value="{{ old('cnpj', $fornecedorEmEdicao?->cnpj) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Contato</label>
                    <input type="text" name="contato_nome" value="{{ old('contato_nome', $fornecedorEmEdicao?->contato_nome) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Telefone</label>
                    <input type="text" name="telefone" value="{{ old('telefone', $fornecedorEmEdicao?->telefone) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Email</label>
                    <input type="email" name="email" value="{{ old('email', $fornecedorEmEdicao?->email) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-[1fr_110px]">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade', $fornecedorEmEdicao?->cidade) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">UF</label>
                        <input type="text" maxlength="2" name="uf" value="{{ old('uf', $fornecedorEmEdicao?->uf) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="md:col-span-2 xl:col-span-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Observacoes</label>
                    <input type="text" name="observacoes" value="{{ old('observacoes', $fornecedorEmEdicao?->observacoes) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div class="flex items-end justify-between gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="ativo" value="1" @checked(old('ativo', $fornecedorEmEdicao?->ativo ?? true)) class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                        Ativo
                    </label>
                    <button type="submit" class="rounded-2xl bg-[#17332a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#22473b]">
                        {{ $fornecedorEmEdicao ? 'Salvar' : 'Cadastrar' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="grid gap-5 xl:grid-cols-2">
            @forelse ($fornecedores as $fornecedor)
                <article class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">{{ $fornecedor->ativo ? 'Ativo' : 'Inativo' }}</p>
                            <h2 class="mt-3 text-2xl font-bold text-[#17332a] font-fraunces">{{ $fornecedor->nome }}</h2>
                            <p class="mt-2 text-sm text-slate-500">{{ $fornecedor->cidade ?: 'Cidade nao informada' }}{{ $fornecedor->uf ? ' - '.$fornecedor->uf : '' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $fornecedor->movimentacoes_count }} mov.</span>
                            <div class="mt-3">
                                <a href="{{ route('nutricionista.fornecedores.index', ['editar' => $fornecedor->id]) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Editar</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 grid gap-2 text-sm text-slate-600">
                        <p><strong>CNPJ:</strong> {{ $fornecedor->cnpj ?: 'Nao informado' }}</p>
                        <p><strong>Contato:</strong> {{ $fornecedor->contato_nome ?: 'Nao informado' }}</p>
                        <p><strong>Email:</strong> {{ $fornecedor->email ?: 'Nao informado' }}</p>
                        <p><strong>Telefone:</strong> {{ $fornecedor->telefone ?: 'Nao informado' }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white/80 p-10 text-center text-sm text-slate-500 xl:col-span-2">
                    Nenhum fornecedor cadastrado ainda.
                </div>
            @endforelse
        </div>
    </div>
</x-nutricionista-layout>
