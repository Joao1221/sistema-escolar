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
                            <th class="px-6 py-3 text-right">Acoes</th>
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
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('psicologia.relatorios_tecnicos.show', $relatorio) }}" class="inline-flex items-center rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-slate-700 transition hover:bg-slate-100">
                                            Visualizar
                                        </a>
                                        <a href="{{ route('psicologia.relatorios_tecnicos.edit', $relatorio) }}" class="inline-flex items-center rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-cyan-800 transition hover:bg-cyan-100">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('psicologia.relatorios_tecnicos.destroy', $relatorio) }}" onsubmit="return confirm('Excluir este relatorio tecnico? Esta acao nao pode ser desfeita.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Excluir relatorio" aria-label="Excluir relatorio" class="inline-flex items-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 transition hover:bg-rose-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M8.5 2a1 1 0 0 0-.894.553L7.382 3H5a1 1 0 1 0 0 2h.293l.842 10.112A2 2 0 0 0 8.128 17h3.744a2 2 0 0 0 1.993-1.888L14.707 5H15a1 1 0 1 0 0-2h-2.382l-.224-.447A1 1 0 0 0 11.5 2h-3Zm1 5a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1Zm3 1a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="sr-only">Excluir</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">Nenhum relatorio tecnico encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">{{ $relatorios->links() }}</div>
        </div>
    </div>
</x-psicologia-layout>
