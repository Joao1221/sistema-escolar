<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Alimentos</h1>
                <p class="mt-2 text-sm text-slate-500">Cadastre os itens utilizados no estoque e no cardapio diario.</p>
            </div>
            <a href="{{ route('secretaria-escolar.alimentacao.alimentos.create') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 hover:bg-emerald-700">
                Novo alimento
            </a>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Nome</th>
                            <th class="px-6 py-3">Categoria</th>
                            <th class="px-6 py-3">Unidade</th>
                            <th class="px-6 py-3 text-right">Estoque minimo</th>
                            <th class="px-6 py-3 text-center">Validade</th>
                            <th class="px-6 py-3 text-right">Acao</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($alimentos as $alimento)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $alimento->nome }}</div>
                                    <div class="text-xs text-slate-500">{{ $alimento->ativo ? 'Ativo' : 'Inativo' }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $alimento->categoria?->nome }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $alimento->unidade_medida }}</td>
                                <td class="px-6 py-4 text-right text-slate-700">{{ number_format((float) $alimento->estoque_minimo, 3, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center text-slate-700">{{ $alimento->controla_validade ? 'Controla' : 'Nao controla' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('secretaria-escolar.alimentacao.alimentos.edit', $alimento) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">Nenhum alimento cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-secretaria-escolar-layout>
