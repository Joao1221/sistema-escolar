<x-professor-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-8">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[1.8rem] border border-[#ead9c3] bg-white px-6 py-5 shadow-sm">
                <p class="text-[11px] uppercase tracking-[0.28em] text-[#9a7d67]">Turmas ativas</p>
                <p class="mt-3 text-4xl font-outfit font-bold text-[#24120d]">{{ $totais['turmas'] }}</p>
            </div>
            <div class="rounded-[1.8rem] border border-[#ead9c3] bg-white px-6 py-5 shadow-sm">
                <p class="text-[11px] uppercase tracking-[0.28em] text-[#9a7d67]">Diários abertos</p>
                <p class="mt-3 text-4xl font-outfit font-bold text-[#24120d]">{{ $totais['diarios'] }}</p>
            </div>
            <div class="rounded-[1.8rem] border border-[#ead9c3] bg-white px-6 py-5 shadow-sm">
                <p class="text-[11px] uppercase tracking-[0.28em] text-[#9a7d67]">Aulas de hoje</p>
                <p class="mt-3 text-4xl font-outfit font-bold text-[#24120d]">{{ $totais['aulas_hoje'] }}</p>
            </div>
            <div class="rounded-[1.8rem] border border-[#ead9c3] bg-white px-6 py-5 shadow-sm">
                <p class="text-[11px] uppercase tracking-[0.28em] text-[#9a7d67]">Pendências abertas</p>
                <p class="mt-3 text-4xl font-outfit font-bold text-[#24120d]">{{ $totais['pendencias_abertas'] }}</p>
            </div>
        </section>

        <section class="grid gap-8 xl:grid-cols-[1.4fr_1fr]">
            <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.28em] text-[#9a7d67]">Ação em destaque</p>
                        <h2 class="mt-2 text-2xl font-outfit font-bold text-[#24120d]">Painel do professor</h2>
                    </div>
                    <div class="rounded-2xl bg-[#f8efe1] px-4 py-3 text-right">
                        <p class="text-xs uppercase tracking-[0.22em] text-[#926d52]">Professor</p>
                        <p class="mt-1 font-semibold text-[#3a2218]">{{ $funcionario->nome }}</p>
                    </div>
                </div>

                @if ($proximaAcao)
                    <div class="mt-6 rounded-[1.6rem] bg-[linear-gradient(135deg,#2d1810_0%,#8b4d28_100%)] p-6 text-white shadow-lg">
                        <p class="text-xs uppercase tracking-[0.28em] text-amber-200">Próxima ação</p>
                        <h3 class="mt-3 text-2xl font-outfit font-semibold">{{ $proximaAcao['titulo'] }}</h3>
                        <p class="mt-2 max-w-2xl text-sm text-white/75">{{ $proximaAcao['descricao'] }}</p>
                        <a href="{{ $proximaAcao['url'] }}" class="mt-5 inline-flex items-center rounded-xl bg-white px-4 py-3 text-xs font-bold uppercase tracking-widest text-[#6d3e25] transition hover:bg-amber-50">
                            Continuar
                        </a>
                    </div>
                @endif

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <a href="{{ route('professor.turmas.index') }}" class="rounded-[1.4rem] border border-[#e7d6c1] bg-[#fffaf4] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-sm font-semibold text-[#2d1810]">Minhas turmas</p>
                        <p class="mt-2 text-sm text-[#7a6355]">Consulte disciplinas, turnos e diários disponíveis para cada turma.</p>
                    </a>
                    <a href="{{ route('professor.horarios.index') }}" class="rounded-[1.4rem] border border-[#e7d6c1] bg-[#fffaf4] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-sm font-semibold text-[#2d1810]">Meu horário</p>
                        <p class="mt-2 text-sm text-[#7a6355]">Veja sua grade semanal com escola, turma e disciplina.</p>
                    </a>
                    <a href="{{ route('professor.diario.index') }}" class="rounded-[1.4rem] border border-[#e7d6c1] bg-[#fffaf4] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-sm font-semibold text-[#2d1810]">Diário eletrônico</p>
                        <p class="mt-2 text-sm text-[#7a6355]">Acesse seus diários para registrar aula, frequência e acompanhamento.</p>
                    </a>
                    <a href="{{ route('professor.diario.create') }}" class="rounded-[1.4rem] border border-[#e7d6c1] bg-[#fffaf4] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-sm font-semibold text-[#2d1810]">Abrir novo diário</p>
                        <p class="mt-2 text-sm text-[#7a6355]">Crie um diário para uma combinação já vinculada ao seu horário.</p>
                    </a>
                </div>
            </div>

            <div class="space-y-8">
                <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Aulas de hoje</h3>
                        <a href="{{ route('professor.horarios.index') }}" class="text-xs font-bold uppercase tracking-widest text-[#8b4d28]">Ver horário</a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse ($aulasHoje as $aula)
                            <div class="rounded-2xl bg-[#f8efe1] px-4 py-4">
                                <p class="font-semibold text-[#2b1710]">{{ $aula->turma->nome }} • {{ $aula->disciplina->nome }}</p>
                                <p class="mt-1 text-sm text-[#7d6556]">{{ \Carbon\Carbon::parse($aula->horario_inicial)->format('H:i') }} às {{ \Carbon\Carbon::parse($aula->horario_final)->format('H:i') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-[#7d6556]">Nenhuma aula prevista para hoje.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Diários recentes</h3>
                        <a href="{{ route('professor.diario.index') }}" class="text-xs font-bold uppercase tracking-widest text-[#8b4d28]">Ver todos</a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse ($diariosRecentes as $diario)
                            <a href="{{ route('professor.diario.show', $diario) }}" class="block rounded-2xl border border-[#ead9c3] px-4 py-4 transition hover:bg-[#fffaf4]">
                                <p class="font-semibold text-[#2b1710]">{{ $diario->turma->nome }}</p>
                                <p class="mt-1 text-sm text-[#7d6556]">{{ $diario->disciplina->nome }} • {{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-[#7d6556]">Nenhum diário aberto ainda.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-professor-layout>
