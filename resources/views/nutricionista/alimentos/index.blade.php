<x-nutricionista-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Base tecnica</p>
                <h2 class="mt-2 text-2xl font-bold text-[#17332a] font-fraunces">Alimentos cadastrados na rede</h2>
                <p class="mt-2 text-sm text-slate-500">Cadastre e mantenha os alimentos usados pelas escolas e pelos cardapios.</p>
            </div>
            <a href="{{ route('nutricionista.alimentos.create') }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#17332a] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#22473b] sm:w-auto">
                Novo alimento
            </a>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Alimento</th>
                            <th class="px-6 py-3">Categoria</th>
                            <th class="px-6 py-3">Unidade</th>
                            <th class="px-6 py-3 text-right">Estoque minimo</th>
                            <th class="px-6 py-3 text-center">Validade</th>
                            <th class="px-6 py-3 text-right">Acoes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($alimentos as $alimento)
                            <tr class="hover:bg-emerald-50/40">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $alimento->nome }}</div>
                                    <div class="text-xs text-slate-500">{{ $alimento->ativo ? 'Ativo' : 'Inativo' }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $alimento->categoria?->nome }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $alimento->unidade_medida }}</td>
                                <td class="px-6 py-4 text-right text-slate-700">{{ number_format((float) $alimento->estoque_minimo, 3, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center text-slate-700">{{ $alimento->controla_validade ? 'Controla' : 'Nao controla' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('nutricionista.alimentos.edit', $alimento) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                    Nenhum alimento cadastrado ainda. Use o botao "Novo alimento" para iniciar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-nutricionista-layout>
