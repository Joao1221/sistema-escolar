<x-professor-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-8">
        <section class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-700 font-semibold">Portal do Professor</p>
                <h2 class="text-3xl font-outfit font-bold text-stone-900">Meus diários ativos</h2>
                <p class="text-stone-600 mt-2">Acesse os diários da sua rotina, registre aula, frequência, planejamentos, observações, ocorrências e pendências.</p>
            </div>

            @can('criar diarios')
                <a href="{{ route('professor.diario.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-[#8b4d28] px-5 py-3 text-sm font-bold uppercase tracking-widest text-white shadow-lg shadow-amber-200 hover:bg-[#6f3c20] transition">
                    Abrir novo diário
                </a>
            @endcan
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.8rem] bg-white p-6 border border-[#e2d3bf] shadow-sm">
                <p class="text-xs uppercase tracking-[0.25em] text-[#9a7d67]">Diários</p>
                <p class="mt-3 text-4xl font-outfit font-bold text-[#24120d]">{{ $diarios->total() }}</p>
            </div>
            <div class="rounded-[1.8rem] bg-white p-6 border border-[#e2d3bf] shadow-sm">
                <p class="text-xs uppercase tracking-[0.25em] text-[#9a7d67]">Turmas no horário</p>
                <p class="mt-3 text-4xl font-outfit font-bold text-[#24120d]">{{ $opcoesCriacao->pluck('turma_id')->unique()->count() }}</p>
            </div>
            <div class="rounded-[1.8rem] bg-white p-6 border border-[#e2d3bf] shadow-sm">
                <p class="text-xs uppercase tracking-[0.25em] text-[#9a7d67]">Disciplinas no horário</p>
                <p class="mt-3 text-4xl font-outfit font-bold text-[#24120d]">{{ $opcoesCriacao->pluck('disciplina_id')->unique()->count() }}</p>
            </div>
        </section>

        <section class="rounded-[2rem] bg-white border border-[#e2d3bf] shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#ead9c3]">
                <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Diários cadastrados</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#ead9c3] text-sm">
                    <thead class="bg-[#fbf6ef] text-[#8b6f5a] uppercase tracking-widest text-xs">
                        <tr>
                            <th class="px-6 py-4 text-left">Turma / Disciplina</th>
                            <th class="px-6 py-4 text-left">Período</th>
                            <th class="px-6 py-4 text-center">Aulas</th>
                            <th class="px-6 py-4 text-center">Ocorrências</th>
                            <th class="px-6 py-4 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0e4d5]">
                        @forelse ($diarios as $diario)
                            <tr class="hover:bg-[#fffaf4] transition">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-[#24120d]">{{ $diario->turma->nome }}</div>
                                    <div class="text-[#7a6355]">{{ $diario->disciplina->nome }} • {{ $diario->escola->nome }}</div>
                                </td>
                                <td class="px-6 py-4 text-[#6f5648]">
                                    {{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }} / {{ $diario->ano_letivo }}
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-[#2f1c14]">{{ $diario->registros_aula_count }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-[#2f1c14]">{{ $diario->ocorrencias_count }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('professor.diario.show', $diario) }}" class="inline-flex rounded-xl border border-[#d0b49a] px-4 py-2 font-semibold text-[#7b4b2a] hover:border-[#8b4d28] hover:bg-[#fffaf4] transition">
                                        Abrir diário
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-[#7a6355]">
                                    Nenhum diário foi criado ainda para este professor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($diarios->hasPages())
                <div class="px-6 py-4 border-t border-[#ead9c3]">
                    {{ $diarios->links() }}
                </div>
            @endif
        </section>
    </div>
</x-professor-layout>
