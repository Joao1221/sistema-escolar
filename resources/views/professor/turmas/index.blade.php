<x-professor-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        @forelse ($turmas as $item)
            <section class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.28em] text-[#9a7d67]">{{ $item->escola->nome }}</p>
                        <h2 class="mt-2 text-2xl font-outfit font-bold text-[#24120d]">{{ $item->turma->nome }} • {{ $item->disciplina->nome }}</h2>
                        <p class="mt-2 text-sm text-[#6f5648]">{{ $item->turma->serie_etapa }} • {{ $item->turno }} • {{ $item->quantidade_aulas_semanais }} aula(s) por semana</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if ($item->diarios->isNotEmpty())
                            <a href="{{ route('professor.diario.show', $item->diarios->first()) }}" class="inline-flex items-center rounded-xl bg-[#2b1710] px-4 py-2 text-xs font-bold uppercase tracking-widest text-white hover:bg-[#8b4d28] transition">
                                Abrir diário
                            </a>
                        @else
                            <a href="{{ route('professor.diario.create') }}" class="inline-flex items-center rounded-xl border border-[#c6a98f] px-4 py-2 text-xs font-bold uppercase tracking-widest text-[#7b4b2a] hover:bg-[#fffaf4] transition">
                                Criar diário
                            </a>
                        @endif
                    </div>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-2">
                    <div class="rounded-[1.5rem] bg-[#f8efe1] p-5">
                        <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-[#8b6f5a]">Horários da turma</h3>
                        <div class="mt-4 space-y-3">
                            @foreach ($item->horarios as $horario)
                                <div class="rounded-2xl bg-white px-4 py-3">
                                    <p class="font-semibold text-[#2b1710]">{{ [1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terca-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sabado'][$horario->dia_semana] ?? 'Dia' }}</p>
                                    <p class="mt-1 text-sm text-[#6f5648]">{{ \Carbon\Carbon::parse($horario->horario_inicial)->format('H:i') }} às {{ \Carbon\Carbon::parse($horario->horario_final)->format('H:i') }} • {{ $horario->ordem_aula }}ª aula</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] bg-[#fffaf4] p-5 border border-[#ead9c3]">
                        <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-[#8b6f5a]">Diários e registros</h3>
                        <div class="mt-4 space-y-3">
                            @forelse ($item->diarios as $diario)
                                <a href="{{ route('professor.diario.show', $diario) }}" class="block rounded-2xl bg-white px-4 py-4 border border-[#ead9c3] hover:shadow-sm transition">
                                    <p class="font-semibold text-[#2b1710]">{{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }} / {{ $diario->ano_letivo }}</p>
                                    <p class="mt-1 text-sm text-[#6f5648]">{{ $diario->registrosAula()->count() }} aula(s) registradas</p>
                                </a>
                            @empty
                                <p class="text-sm text-[#7d6556]">Nenhum diário criado ainda para esta combinação.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-8 text-center text-[#6f5648] shadow-sm">
                Nenhuma turma vinculada ao professor foi encontrada.
            </div>
        @endforelse
    </div>
</x-professor-layout>
