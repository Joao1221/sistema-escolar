<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Registro Sigiloso</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ $atendimento->nome_atendido }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ ucfirst($atendimento->tipo_publico) }} | {{ ucfirst($atendimento->tipo_atendimento) }} | {{ $atendimento->data_agendada->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('secretaria-escolar.psicossocial.historico') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Historico</a>
                <a href="{{ route('secretaria-escolar.psicossocial.relatorios') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Relatorios</a>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_1fr]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Resumo do atendimento</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Escola</span><p class="mt-1 font-semibold text-slate-900">{{ $atendimento->escola?->nome }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Profissional responsavel</span><p class="mt-1 font-semibold text-slate-900">{{ $atendimento->profissionalResponsavel?->nome ?: 'Nao informado' }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Status</span><p class="mt-1 font-semibold text-slate-900">{{ ucfirst($atendimento->status) }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Sigilo</span><p class="mt-1 font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $atendimento->nivel_sigilo)) }}</p></div>
                </div>
                <div class="mt-5 space-y-4 text-sm text-slate-700">
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Motivo da demanda</span><p class="mt-1 whitespace-pre-line">{{ $atendimento->motivo_demanda }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Resumo sigiloso</span><p class="mt-1 whitespace-pre-line">{{ $atendimento->resumo_sigiloso ?: 'Nao informado' }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Observacoes restritas</span><p class="mt-1 whitespace-pre-line">{{ $atendimento->observacoes_restritas ?: 'Nao informado' }}</p></div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Acoes sigilosas</h2>
                <div class="mt-5 space-y-6 text-sm">
                    <form method="POST" action="{{ route('secretaria-escolar.psicossocial.planos.store', $atendimento) }}" class="space-y-3 rounded-2xl border border-slate-200 p-4">
                        @csrf
                        <h3 class="font-semibold text-slate-900">Plano de intervencao</h3>
                        <input type="text" name="objetivo_geral" placeholder="Objetivo geral" class="w-full rounded-xl border-slate-300 shadow-sm">
                        <textarea name="estrategias" rows="3" placeholder="Estrategias" class="w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        <div class="grid gap-3 md:grid-cols-2">
                            <input type="date" name="data_inicio" class="rounded-xl border-slate-300 shadow-sm">
                            <select name="status" class="rounded-xl border-slate-300 shadow-sm">
                                <option value="ativo">Ativo</option>
                                <option value="em_acompanhamento">Em acompanhamento</option>
                                <option value="concluido">Concluido</option>
                            </select>
                        </div>
                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white">Salvar plano</button>
                    </form>

                    <form method="POST" action="{{ route('secretaria-escolar.psicossocial.encaminhamentos.store', $atendimento) }}" class="space-y-3 rounded-2xl border border-slate-200 p-4">
                        @csrf
                        <h3 class="font-semibold text-slate-900">Encaminhamento</h3>
                        <div class="grid gap-3 md:grid-cols-2">
                            <select name="tipo" class="rounded-xl border-slate-300 shadow-sm">
                                <option value="interno">Interno</option>
                                <option value="externo">Externo</option>
                            </select>
                            <input type="text" name="destino" placeholder="Destino" class="rounded-xl border-slate-300 shadow-sm">
                        </div>
                        <textarea name="motivo" rows="3" placeholder="Motivo" class="w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        <input type="date" name="data_encaminhamento" class="rounded-xl border-slate-300 shadow-sm">
                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white">Salvar encaminhamento</button>
                    </form>

                    <form method="POST" action="{{ route('secretaria-escolar.psicossocial.relatorios.store', $atendimento) }}" class="space-y-3 rounded-2xl border border-slate-200 p-4">
                        @csrf
                        <h3 class="font-semibold text-slate-900">Relatorio tecnico</h3>
                        <div class="grid gap-3 md:grid-cols-2">
                            <select name="tipo_relatorio" class="rounded-xl border-slate-300 shadow-sm">
                                <option value="parecer_inicial">Parecer inicial</option>
                                <option value="acompanhamento">Acompanhamento</option>
                                <option value="encaminhamento">Encaminhamento</option>
                                <option value="sintese">Sintese</option>
                            </select>
                            <input type="date" name="data_emissao" value="{{ now()->toDateString() }}" class="rounded-xl border-slate-300 shadow-sm">
                        </div>
                        <input type="text" name="titulo" placeholder="Titulo do relatorio" class="w-full rounded-xl border-slate-300 shadow-sm">
                        <textarea name="conteudo_sigiloso" rows="4" placeholder="Conteudo tecnico" class="w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white">Emitir relatorio</button>
                    </form>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Planos de intervencao</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->planosIntervencao as $plano)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="font-semibold text-slate-900">{{ $plano->objetivo_geral }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $plano->data_inicio->format('d/m/Y') }} | {{ ucfirst($plano->status) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum plano cadastrado.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Encaminhamentos</h2>
                    <form method="POST" action="{{ route('secretaria-escolar.psicossocial.casos.store', $atendimento) }}" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="aluno_id" value="{{ $atendimento->atendivel instanceof \App\Models\Aluno ? $atendimento->atendivel->id : '' }}">
                        <input type="hidden" name="funcionario_id" value="{{ $atendimento->atendivel instanceof \App\Models\Funcionario ? $atendimento->atendivel->id : '' }}">
                        <input type="hidden" name="data_ocorrencia" value="{{ now()->toDateString() }}">
                        <input type="hidden" name="titulo" value="Caso disciplinar vinculado ao atendimento">
                        <input type="hidden" name="descricao_sigilosa" value="Registro disciplinar criado a partir do atendimento sigiloso.">
                        <input type="hidden" name="status" value="aberto">
                        <button type="submit" class="rounded-xl border border-amber-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-amber-700 hover:bg-amber-50">Abrir caso</button>
                    </form>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->encaminhamentos as $encaminhamento)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="font-semibold text-slate-900">{{ ucfirst($encaminhamento->tipo) }} - {{ $encaminhamento->destino }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $encaminhamento->data_encaminhamento->format('d/m/Y') }} | {{ ucfirst($encaminhamento->status) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum encaminhamento registrado.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Relatorios tecnicos</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->relatoriosTecnicos as $relatorio)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="font-semibold text-slate-900">{{ $relatorio->titulo }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $relatorio->data_emissao->format('d/m/Y') }} | {{ ucfirst(str_replace('_', ' ', $relatorio->tipo_relatorio)) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum relatorio emitido.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-secretaria-escolar-layout>
