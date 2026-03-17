<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Relatorios tecnicos iniciais</h1>
            <p class="mt-2 text-sm text-slate-500">Lista sigilosa de documentos tecnicos emitidos no modulo.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Titulo</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3">Atendimento</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($relatorios as $relatorio)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-700">{{ $relatorio->data_emissao->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $relatorio->titulo }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst(str_replace('_', ' ', $relatorio->tipo_relatorio)) }}</td>
                                <td class="px-6 py-4">
                                    @if ($relatorio->atendimento)
                                        <a href="{{ route('secretaria-escolar.psicossocial.show', $relatorio->atendimento) }}" class="text-emerald-700 hover:text-emerald-800">Abrir atendimento</a>
                                    @else
                                        <span class="text-slate-500">Sem atendimento vinculado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">Nenhum relatorio tecnico emitido.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-secretaria-escolar-layout>
