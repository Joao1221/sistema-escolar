<x-secretaria-escolar-layout>
    <div class="space-y-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">Consulta de diarios</h2>
            <p class="text-slate-500 mt-2">Acompanhamento pedagogico dos registros do professor sem abrir ainda os portais completos de coordenacao e direcao.</p>
        </div>

        <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-widest text-xs">
                        <tr>
                            <th class="px-6 py-4 text-left">Turma / Disciplina</th>
                            <th class="px-6 py-4 text-left">Professor</th>
                            <th class="px-6 py-4 text-left">Periodo</th>
                            <th class="px-6 py-4 text-center">Aulas</th>
                            <th class="px-6 py-4 text-right">Acao</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($diarios as $diario)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $diario->turma->nome }}</div>
                                    <div class="text-slate-500">{{ $diario->disciplina->nome }} • {{ $diario->escola->nome }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $diario->professor->nome }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }} / {{ $diario->ano_letivo }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-slate-800">{{ $diario->registros_aula_count }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('secretaria-escolar.diarios.show', $diario) }}" class="inline-flex rounded-xl border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:border-emerald-500 hover:text-emerald-700 transition">
                                        Consultar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">Nenhum diario encontrado para as escolas vinculadas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($diarios->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $diarios->links() }}
                </div>
            @endif
        </div>
    </div>
</x-secretaria-escolar-layout>
