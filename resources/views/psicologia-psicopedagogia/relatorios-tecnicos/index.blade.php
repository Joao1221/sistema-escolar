<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Producoes restritas</p>
            <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Relatorios tecnicos</h1>
            <p class="mt-2 text-sm text-slate-500">Lista sigilosa dos relatorios emitidos para acompanhamento e encaminhamento tecnico.</p>
        </div>

        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Titulo</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3">Atendido</th>
                            <th class="px-6 py-3">Escola</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($relatorios as $relatorio)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-700">{{ $relatorio->data_emissao?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-semibold text-[#14363a]">{{ $relatorio->titulo }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst(str_replace('_', ' ', $relatorio->tipo_relatorio)) }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $relatorio->atendimento?->nome_atendido ?? 'Sem vinculo' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $relatorio->atendimento?->escola?->nome ?? $relatorio->escola?->nome }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Nenhum relatorio tecnico encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">{{ $relatorios->links() }}</div>
        </div>
    </div>
</x-psicologia-layout>
