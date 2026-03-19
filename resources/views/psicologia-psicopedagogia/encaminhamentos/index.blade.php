<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Fluxo tecnico</p>
            <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Encaminhamentos</h1>
            <p class="mt-2 text-sm text-slate-500">Encaminhamentos internos e externos registrados no ambiente restrito.</p>
        </div>

        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Atendimento</th>
                            <th class="px-6 py-3">Destino</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($encaminhamentos as $encaminhamento)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-700">{{ $encaminhamento->data_encaminhamento?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-slate-700">
                                    <a href="{{ route('psicologia.show', $encaminhamento->atendimento) }}" class="font-semibold text-[#14363a] hover:text-cyan-700">{{ $encaminhamento->atendimento?->nome_atendido }}</a>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $encaminhamento->destino }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst($encaminhamento->tipo) }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst(str_replace('_', ' ', $encaminhamento->status)) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Nenhum encaminhamento encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">{{ $encaminhamentos->links() }}</div>
        </div>
    </div>
</x-psicologia-layout>
