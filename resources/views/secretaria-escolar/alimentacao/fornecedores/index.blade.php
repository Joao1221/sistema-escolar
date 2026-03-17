<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Fornecedores</h1>
                <p class="mt-2 text-sm text-slate-500">Cadastre parceiros para rastrear entradas e lotes dos alimentos.</p>
            </div>
            <a href="{{ route('secretaria-escolar.alimentacao.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Voltar ao modulo</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_1.35fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">{{ $fornecedorEmEdicao ? 'Editar fornecedor' : 'Novo fornecedor' }}</h2>
                <form method="POST" action="{{ $fornecedorEmEdicao ? route('secretaria-escolar.alimentacao.fornecedores.update', $fornecedorEmEdicao) : route('secretaria-escolar.alimentacao.fornecedores.store') }}" class="mt-5 grid gap-4">
                    @csrf
                    @if ($fornecedorEmEdicao)
                        @method('PUT')
                    @endif
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Nome</label>
                        <input type="text" name="nome" value="{{ old('nome', $fornecedorEmEdicao?->nome) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">CNPJ</label>
                            <input type="text" name="cnpj" value="{{ old('cnpj', $fornecedorEmEdicao?->cnpj) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Contato</label>
                            <input type="text" name="contato_nome" value="{{ old('contato_nome', $fornecedorEmEdicao?->contato_nome) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Telefone</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $fornecedorEmEdicao?->telefone) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Email</label>
                            <input type="email" name="email" value="{{ old('email', $fornecedorEmEdicao?->email) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Cidade</label>
                            <input type="text" name="cidade" value="{{ old('cidade', $fornecedorEmEdicao?->cidade) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">UF</label>
                            <input type="text" name="uf" maxlength="2" value="{{ old('uf', $fornecedorEmEdicao?->uf) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes</label>
                        <textarea name="observacoes" rows="3" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('observacoes', $fornecedorEmEdicao?->observacoes) }}</textarea>
                    </div>
                    <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                        <input type="checkbox" name="ativo" value="1" @checked(old('ativo', $fornecedorEmEdicao?->ativo ?? true)) class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                        Fornecedor ativo
                    </label>
                    <div class="flex gap-3">
                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                            {{ $fornecedorEmEdicao ? 'Salvar alteracoes' : 'Cadastrar fornecedor' }}
                        </button>
                        @if ($fornecedorEmEdicao)
                            <a href="{{ route('secretaria-escolar.alimentacao.fornecedores.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Lista de fornecedores</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($fornecedores as $fornecedor)
                        <div class="flex items-start justify-between gap-4 px-6 py-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <p class="font-semibold text-slate-900">{{ $fornecedor->nome }}</p>
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $fornecedor->ativo ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $fornecedor->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-slate-500">{{ $fornecedor->cidade ?: 'Cidade nao informada' }}{{ $fornecedor->uf ? ' - '.$fornecedor->uf : '' }}</p>
                                <p class="text-xs text-slate-500">{{ $fornecedor->email ?: 'Sem email' }}{{ $fornecedor->telefone ? ' | '.$fornecedor->telefone : '' }}</p>
                            </div>
                            <a href="{{ route('secretaria-escolar.alimentacao.fornecedores.index', ['editar' => $fornecedor->id]) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Editar</a>
                        </div>
                    @empty
                        <p class="px-6 py-8 text-sm text-slate-500">Nenhum fornecedor cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-secretaria-escolar-layout>
