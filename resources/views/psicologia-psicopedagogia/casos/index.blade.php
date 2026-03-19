<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Sigilo disciplinar</p>
            <h1 class="mt-2 text-3xl font-bold text-[#14363a]">Casos disciplinares</h1>
            <p class="mt-2 text-sm text-slate-500">Registros restritos associados aos acompanhamentos tecnicos quando aplicavel.</p>
        </div>

        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Titulo</th>
                            <th class="px-6 py-3">Escola</th>
                            <th class="px-6 py-3">Situacao</th>
                            <th class="px-6 py-3">Atendimento vinculado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($casos as $caso)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-700">{{ $caso->data_ocorrencia?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-semibold text-[#14363a]">{{ $caso->titulo }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $caso->escola?->nome }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst(str_replace('_', ' ', $caso->status)) }}</td>
                                <td class="px-6 py-4 text-slate-700">
                                    @if ($caso->atendimento)
                                        <a href="{{ route('psicologia.show', $caso->atendimento) }}" class="font-semibold text-[#14363a] hover:text-cyan-700">{{ $caso->atendimento->nome_atendido }}</a>
                                    @else
                                        <span class="text-slate-500">Sem vinculo</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Nenhum caso encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">{{ $casos->links() }}</div>
        </div>
    </div>
</x-psicologia-layout>
