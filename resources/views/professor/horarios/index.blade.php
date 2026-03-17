<x-professor-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="grid gap-6 xl:grid-cols-2">
        @forelse ($horariosAgrupados as $grupo)
            <section class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-outfit font-bold text-[#24120d]">{{ $grupo['rotulo'] }}</h2>
                    <span class="rounded-full bg-[#f8efe1] px-4 py-2 text-xs font-bold uppercase tracking-[0.24em] text-[#8b6f5a]">
                        {{ $grupo['itens']->count() }} aula(s)
                    </span>
                </div>

                <div class="mt-5 space-y-4">
                    @foreach ($grupo['itens'] as $horario)
                        <div class="rounded-[1.4rem] border border-[#ead9c3] bg-[#fffaf4] p-5">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-lg font-semibold text-[#2b1710]">{{ $horario->turma->nome }} • {{ $horario->disciplina->nome }}</p>
                                    <p class="mt-1 text-sm text-[#6f5648]">{{ $horario->escola->nome }}</p>
                                </div>
                                <div class="rounded-xl bg-white px-4 py-3 text-sm font-semibold text-[#5b4033] shadow-sm">
                                    {{ \Carbon\Carbon::parse($horario->horario_inicial)->format('H:i') }} às {{ \Carbon\Carbon::parse($horario->horario_final)->format('H:i') }}
                                </div>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <span class="rounded-full bg-[#f3e5d4] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-[#8b6f5a]">
                                    {{ $horario->ordem_aula }}ª aula
                                </span>
                                <a href="{{ route('professor.diario.index') }}" class="rounded-full border border-[#d8b89b] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-[#7b4b2a] hover:bg-white transition">
                                    Ir para diário
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-8 text-center text-[#6f5648] shadow-sm xl:col-span-2">
                Nenhum horário ativo foi encontrado para o professor.
            </div>
        @endforelse
    </div>
</x-professor-layout>
