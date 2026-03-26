<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    @php
        $encerrado = $atendimento->status === 'encerrado';
    @endphp
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Registro sigiloso</p>
                <h1 class="mt-2 text-3xl font-bold text-[#14363a]">{{ $atendimento->nome_atendido }}</h1>
                <p class="mt-2 text-sm text-slate-500">
                    {{ ucfirst($atendimento->tipo_publico) }} | 
                    {{ ucfirst($atendimento->tipo_atendimento) }} | 
                    {{ $atendimento->data_agendada->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="flex gap-3 flex-wrap">
                @if ($atendimento->status === 'agendado' && !$encerrado)
                    <form method="POST" action="{{ route('psicologia.atendimento.finalizar', $atendimento) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-xl border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Iniciar Atendimento</button>
                    </form>
                @endif
                @if (in_array($atendimento->status, ['em_atendimento', 'realizado', 'em_acompanhamento']) && !$encerrado)
                    <button type="button" onclick="showModal('modal-sessao')" class="rounded-xl border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Registrar Sessao
                    </button>
                    <button
                        type="button"
                        onclick="showModal('modal-devolutiva')"
                        class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                        style="background-color:#fbbf24;border:1px solid #d97706;color:#000;"
                        onmouseover="this.style.backgroundColor='#f59e0b'"
                        onmouseout="this.style.backgroundColor='#fbbf24'"
                    >
                        Devolutiva
                    </button>
                    <button
                        type="button"
                        onclick="showModal('modal-reavaliacao')"
                        class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                        style="background-color:#fff;border:1px solid #2563eb;color:#2563eb;"
                        onmouseover="this.style.backgroundColor='#eef2ff'"
                        onmouseout="this.style.backgroundColor='#fff'"
                    >
                        Reavaliacao
                    </button>
                @endif
                @if ($encerrado)
                    <form method="POST" action="{{ route('psicologia.atendimento.reabrir', $atendimento) }}">
                        @csrf
                        <button type="submit" class="rounded-xl border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            Reabrir atendimento
                        </button>
                    </form>
                @endif
                <a href="{{ route('psicologia.atendimentos.relatorio_sessoes', $atendimento) }}" target="_blank" class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition" style="background-color:#fbbf24;border:1px solid #d97706;color:#000;" onmouseover="this.style.backgroundColor='#f59e0b'" onmouseout="this.style.backgroundColor='#fbbf24'">
                    Imprimir relatorio do atendimento
                </a>
                @if (in_array($atendimento->status, ['em_atendimento', 'em_acompanhamento', 'agendado']) && !$encerrado)
                    <button type="button" onclick="showModal('modal-encerrar')" class="rounded-xl border border-red-600 bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                        Encerrar
                    </button>
                @endif
                <a href="{{ route('psicologia.historico.index') }}" class="rounded-xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">Historico</a>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_1fr]">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Resumo do atendimento</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Escola</span><p class="mt-1 font-semibold text-[#14363a]">{{ $atendimento->escola?->nome }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Profissional responsavel</span><p class="mt-1 font-semibold text-[#14363a]">{{ $atendimento->profissionalResponsavel?->nome ?: 'Nao informado' }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Status</span><p class="mt-1 font-semibold text-[#14363a]">{{ ucfirst(str_replace('_', ' ', $atendimento->status)) }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Sigilo</span><p class="mt-1 font-semibold text-[#14363a]">{{ ucfirst(str_replace('_', ' ', $atendimento->nivel_sigilo)) }}</p></div>
                </div>
                <div class="mt-5 space-y-4 text-sm text-slate-700">
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Motivo da demanda</span><p class="mt-1 whitespace-pre-line">{{ $atendimento->motivo_demanda }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Resumo sigiloso</span><p class="mt-1 whitespace-pre-line">{{ $atendimento->resumo_sigiloso ?: 'Nao informado' }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Observacoes restritas</span><p class="mt-1 whitespace-pre-line">{{ $atendimento->observacoes_restritas ?: 'Nao informado' }}</p></div>
                    @if ($atendimento->status === 'encerrado')
                        <div class="mt-4 rounded-xl bg-red-50 p-4">
                            <h3 class="text-sm font-bold text-red-700">Atendimento Encerrado</h3>
                            @if ($atendimento->data_encerramento)
                                <p class="mt-1 text-xs text-red-600">Data: {{ $atendimento->data_encerramento->format('d/m/Y') }}</p>
                            @endif
                            @if ($atendimento->motivo_encerramento)
                                <p class="mt-2 text-sm text-red-700">{{ $atendimento->motivo_encerramento }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Acoes sigilosas</h2>
                <div class="mt-5 space-y-6 text-sm">
                    @if (!$encerrado)
                    <form method="POST" action="{{ route('psicologia.planos.store', $atendimento) }}" class="space-y-3 rounded-2xl border border-slate-200 p-4">
                        @csrf
                        <h3 class="font-bold text-white uppercase text-sm tracking-widest bg-emerald-600 px-3 py-2 rounded-lg">Plano de intervencao</h3>
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
                        <button type="submit" class="rounded-xl border border-black bg-black px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-900">Salvar plano</button>
                    </form>

                    <form method="POST" action="{{ route('psicologia.encaminhamentos.store', $atendimento) }}" class="space-y-3 rounded-2xl border border-slate-200 p-4">
                        @csrf
                        <h3 class="font-bold text-white uppercase text-sm tracking-widest bg-amber-600 px-3 py-2 rounded-lg">Encaminhamento</h3>
                        <div class="grid gap-3 md:grid-cols-2">
                            <select name="tipo" class="rounded-xl border-slate-300 shadow-sm">
                                <option value="interno">Interno</option>
                                <option value="externo">Externo</option>
                            </select>
                            <input type="text" name="destino" placeholder="Destino" class="rounded-xl border-slate-300 shadow-sm">
                        </div>
                        <textarea name="motivo" rows="3" placeholder="Motivo" class="w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        <div class="grid gap-3 md:grid-cols-2">
                            <input type="date" name="data_encaminhamento" class="rounded-xl border-slate-300 shadow-sm">
                            <select name="status" class="rounded-xl border-slate-300 shadow-sm">
                                <option value="emitido">Emitido</option>
                                <option value="em_acompanhamento">Em acompanhamento</option>
                                <option value="concluido">Concluido</option>
                            </select>
                        </div>
                        <input type="text" name="profissional_destino" placeholder="Profissional de destino (opcional)" class="w-full rounded-xl border-slate-300 shadow-sm">
                        <input type="text" name="instituicao_destino" placeholder="Instituicao de destino (opcional)" class="w-full rounded-xl border-slate-300 shadow-sm">
                        <textarea name="orientacoes_sigilosas" rows="3" placeholder="Orientacoes sigilosas (opcional)" class="w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        <input type="date" name="retorno_previsto_em" class="rounded-xl border-slate-300 shadow-sm">
                        <button type="submit" class="rounded-xl border border-black bg-black px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-900">Salvar encaminhamento</button>
                    </form>

                    <form method="POST" action="{{ route('psicologia.relatorios_tecnicos.store', $atendimento) }}" class="space-y-3 rounded-2xl border border-slate-200 p-4">
                        @csrf
                        <h3 class="font-bold text-white uppercase text-sm tracking-widest bg-indigo-600 px-3 py-2 rounded-lg">Relatorio tecnico</h3>
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
                        <button type="submit" class="rounded-xl border border-black bg-black px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-900">Emitir relatorio</button>
                    </form>
                    @endif
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Planos de intervencao</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->planosIntervencao as $plano)
                        <details class="group rounded-2xl border border-slate-100 p-4 shadow-[0_4px_12px_rgba(15,23,42,0.04)]" @if($loop->first) open @endif>
                            <summary class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <p class="font-semibold text-[#14363a]">{{ $plano->objetivo_geral }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $plano->data_inicio->format('d/m/Y') }} | {{ ucfirst(str_replace('_', ' ', $plano->status)) }}</p>
                                </div>
                                @if (!$encerrado)
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('psicologia.plano.edit', $plano) }}" class="inline-flex items-center rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-[11px] font-semibold uppercase tracking-widest text-cyan-800 transition hover:bg-cyan-100">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('psicologia.plano.destroy', $plano) }}" onsubmit="return confirm('Excluir este plano? Esta acao nao pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Excluir plano" class="inline-flex items-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 transition hover:bg-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 2a1 1 0 0 0-.894.553L7.382 3H5a1 1 0 1 0 0 2h.293l.842 10.112A2 2 0 0 0 8.128 17h3.744a2 2 0 0 0 1.993-1.888L14.707 5H15a1 1 0 1 0 0-2h-2.382l-.224-.447A1 1 0 0 0 11.5 2h-3Zm1 5a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1Zm3 1a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <span class="text-xs font-semibold text-cyan-700 group-open:rotate-180 transition">▼</span>
                                </div>
                            </summary>
                            <div class="mt-3 grid gap-3 md:grid-cols-2 text-sm text-slate-700">
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Objetivos especificos</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $plano->objetivos_especificos ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Estrategias</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $plano->estrategias ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Responsaveis pela execucao</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $plano->responsaveis_execucao ?: 'Nao informado' }}</p>
                                </div>
                                @if($plano->data_fim)
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Data fim</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $plano->data_fim->format('d/m/Y') }}</p>
                                </div>
                                @endif
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Observacoes sigilosas</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $plano->observacoes_sigilosas ?: 'Nao informado' }}</p>
                                </div>
                            </div>
                        </details>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum plano cadastrado.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Encaminhamentos</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->encaminhamentos as $encaminhamento)
                        <details class="group rounded-2xl border border-slate-100 p-4 shadow-[0_4px_12px_rgba(15,23,42,0.04)]" @if($loop->first) open @endif>
                            <summary class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <p class="font-semibold text-[#14363a]">{{ ucfirst(str_replace('_', ' ', $encaminhamento->tipo)) }} - {{ $encaminhamento->destino }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $encaminhamento->data_encaminhamento->format('d/m/Y') }} | {{ ucfirst(str_replace('_', ' ', $encaminhamento->status)) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if (!$encerrado)
                                    <a href="{{ route('psicologia.encaminhamento.edit', $encaminhamento) }}" class="inline-flex items-center rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-[11px] font-semibold uppercase tracking-widest text-cyan-800 transition hover:bg-cyan-100">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('psicologia.encaminhamento.destroy', $encaminhamento) }}" onsubmit="return confirm('Excluir este encaminhamento? Esta acao nao pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Excluir encaminhamento" class="inline-flex items-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 transition hover:bg-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 2a1 1 0 0 0-.894.553L7.382 3H5a1 1 0 1 0 0 2h.293l.842 10.112A2 2 0 0 0 8.128 17h3.744a2 2 0 0 0 1.993-1.888L14.707 5H15a1 1 0 1 0 0-2h-2.382l-.224-.447A1 1 0 0 0 11.5 2h-3Zm1 5a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1Zm3 1a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <span class="text-xs font-semibold text-cyan-700 group-open:rotate-180 transition">▼</span>
                                </div>
                            </summary>
                            <div class="mt-3 grid gap-3 md:grid-cols-2 text-sm text-slate-700">
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Motivo</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $encaminhamento->motivo ?: 'Nao informado' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Profissional de destino</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $encaminhamento->profissional_destino ?: 'Nao informado' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Instituicao de destino</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $encaminhamento->instituicao_destino ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Orientacoes sigilosas</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $encaminhamento->orientacoes_sigilosas ?: 'Nao informado' }}</p>
                                </div>
                                @if($encaminhamento->retorno_previsto_em)
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Retorno previsto em</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $encaminhamento->retorno_previsto_em->format('d/m/Y') }}</p>
                                </div>
                                @endif
                            </div>
                        </details>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum encaminhamento registrado.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Relatorios tecnicos</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->relatoriosTecnicos as $relatorio)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-[#14363a]">{{ $relatorio->titulo }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $relatorio->data_emissao->format('d/m/Y') }} | {{ ucfirst(str_replace('_', ' ', $relatorio->tipo_relatorio)) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('psicologia.relatorios_tecnicos.show', $relatorio) }}" class="inline-flex items-center rounded-xl border border-slate-300 px-3 py-2 text-[11px] font-semibold uppercase tracking-widest text-slate-700 transition hover:bg-slate-100">
                                        Abrir
                                    </a>
                                    @if (!$encerrado)
                                    <a href="{{ route('psicologia.relatorios_tecnicos.edit', $relatorio) }}" class="inline-flex items-center rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-[11px] font-semibold uppercase tracking-widest text-cyan-800 transition hover:bg-cyan-100">
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
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum relatorio emitido.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Sessoes realizadas</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->sessoes as $sessao)
                        <details class="group rounded-2xl border border-slate-100 p-4 shadow-[0_4px_12px_rgba(15,23,42,0.04)]" @if($loop->first) open @endif>
                            <summary class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <p class="font-semibold text-[#14363a]">{{ $sessao->data_sessao->format('d/m/Y') }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ ucfirst($sessao->tipo_sessao) }} | {{ ucfirst($sessao->status) }}</p>
                                </div>
                                <span class="text-xs font-semibold text-cyan-700 group-open:rotate-180 transition">▼</span>
                            </summary>
                            <div class="mt-3 grid gap-3 md:grid-cols-2 text-sm text-slate-700">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Objetivo</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $sessao->objetivo_sessao ?: 'Nao informado' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Relato</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $sessao->relato_sessao ?: 'Nao informado' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Estrategias</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $sessao->estrategias_utilizadas ?: 'Nao informado' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Comportamento observado</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $sessao->comportamento_observado ?: 'Nao informado' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Evolucao percebida</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $sessao->evolucao_percebida ?: 'Nao informado' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Proximo passo</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $sessao->proximo_passo ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Encaminhamentos definidos</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $sessao->encaminhamentos_definidos ?: 'Nao informado' }}</p>
                                </div>
                            </div>
                        </details>
                    @empty
                        <p class="text-sm text-slate-500">Nenhuma sessao registrada.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Devolutivas</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->devolutivas as $devolutiva)
                        <details class="group rounded-2xl border border-slate-100 p-4 shadow-[0_4px_12px_rgba(15,23,42,0.04)]" @if($loop->first) open @endif>
                            <summary class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <p class="font-semibold text-[#14363a]">{{ ucfirst($devolutiva->destinatario) }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $devolutiva->data_devolutiva->format('d/m/Y') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if (!$encerrado)
                                    <a href="{{ route('psicologia.devolutiva.edit', $devolutiva) }}" class="inline-flex items-center rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-[11px] font-semibold uppercase tracking-widest text-cyan-800 transition hover:bg-cyan-100">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('psicologia.devolutiva.destroy', $devolutiva) }}" onsubmit="return confirm('Excluir esta devolutiva? Esta acao nao pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Excluir devolutiva" class="inline-flex items-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 transition hover:bg-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 2a1 1 0 0 0-.894.553L7.382 3H5a1 1 0 1 0 0 2h.293l.842 10.112A2 2 0 0 0 8.128 17h3.744a2 2 0 0 0 1.993-1.888L14.707 5H15a1 1 0 1 0 0-2h-2.382l-.224-.447A1 1 0 0 0 11.5 2h-3Zm1 5a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1Zm3 1a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <span class="text-xs font-semibold text-cyan-700 group-open:rotate-180 transition">▼</span>
                                </div>
                            </summary>
                            <div class="mt-3 grid gap-3 md:grid-cols-2 text-sm text-slate-700">
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Resumo da devolutiva</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $devolutiva->resumo_devolutiva ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Orientacoes</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $devolutiva->orientacoes ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Encaminhamentos combinados</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $devolutiva->encaminhamentos_combinados ?: 'Nao informado' }}</p>
                                </div>
                            </div>
                        </details>
                    @empty
                        <p class="text-sm text-slate-500">Nenhuma devolutiva registrada.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Reavaliacoes</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($atendimento->reavaliacoes as $reavaliacao)
                        <details class="group rounded-2xl border border-slate-100 p-4 shadow-[0_4px_12px_rgba(15,23,42,0.04)]" @if($loop->first) open @endif>
                            <summary class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <p class="font-semibold text-[#14363a]">{{ $reavaliacao->data_reavaliacao->format('d/m/Y') }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ ucfirst(str_replace('_', ' ', $reavaliacao->decisao)) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if (!$encerrado)
                                    <a href="{{ route('psicologia.reavaliacao.edit', $reavaliacao) }}" class="inline-flex items-center rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-[11px] font-semibold uppercase tracking-widest text-cyan-800 transition hover:bg-cyan-100">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('psicologia.reavaliacao.destroy', $reavaliacao) }}" onsubmit="return confirm('Excluir esta reavaliacao? Esta acao nao pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Excluir reavaliacao" class="inline-flex items-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 transition hover:bg-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 2a1 1 0 0 0-.894.553L7.382 3H5a1 1 0 1 0 0 2h.293l.842 10.112A2 2 0 0 0 8.128 17h3.744a2 2 0 0 0 1.993-1.888L14.707 5H15a1 1 0 1 0 0-2h-2.382l-.224-.447A1 1 0 0 0 11.5 2h-3Zm1 5a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1Zm3 1a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <span class="text-xs font-semibold text-cyan-700 group-open:rotate-180 transition">▼</span>
                                </div>
                            </summary>
                            <div class="mt-3 grid gap-3 md:grid-cols-2 text-sm text-slate-700">
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Progresso observado</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $reavaliacao->progresso_observado ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Dificuldades persistentes</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $reavaliacao->dificuldades_persistentes ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Ajuste do plano</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $reavaliacao->ajuste_plano ?: 'Nao informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Justificativa</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $reavaliacao->justificativa ?: 'Nao informado' }}</p>
                                </div>
                                @if($reavaliacao->proxima_reavaliacao)
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-500">Proxima reavaliacao</p>
                                    <p class="mt-1 whitespace-pre-line">{{ $reavaliacao->proxima_reavaliacao->format('d/m/Y') }}</p>
                                </div>
                                @endif
                            </div>
                        </details>
                    @empty
                        <p class="text-sm text-slate-500">Nenhuma reavaliacao registrada.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    <div id="modal-sessao" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="hideModal('modal-sessao')"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-[#14363a]">Registrar Sessao</h3>
                    <button onclick="hideModal('modal-sessao')" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <form method="POST" action="{{ route('psicologia.atendimento.sessao.store', $atendimento) }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data *</label>
                            <input type="date" name="data_sessao" value="{{ now()->toDateString() }}" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipo de sessao *</label>
                            <select name="tipo_sessao" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="avaliacao">Avaliacao</option>
                                <option value="intervencao" selected>Intervencao</option>
                                <option value="retorno">Retorno</option>
                                <option value="emergencial">Emergencial</option>
                                <option value="acolhimento">Acolhimento</option>
                                <option value="devolutiva">Devolutiva</option>
                                <option value="reavaliacao">Reavaliacao</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Hora inicio</label>
                            <input type="time" name="hora_inicio" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Hora fim</label>
                            <input type="time" name="hora_fim" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Objetivo da sessao</label>
                            <textarea name="objetivo_sessao" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Relato da sessao</label>
                            <textarea name="relato_sessao" rows="3" placeholder="Descreva o que ocorreu na sessao..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Estrategias utilizadas</label>
                            <textarea name="estrategias_utilizadas" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Comportamento observado</label>
                            <textarea name="comportamento_observado" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Evolucao percebida</label>
                            <textarea name="evolucao_percebida" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Proximo passo</label>
                            <textarea name="proximo_passo" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
                            <select name="status" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="realizado">Realizado</option>
                                <option value="remarcado">Remarcado</option>
                                <option value="faltou">Faltou</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="sticky bottom-0 flex justify-end gap-3 bg-white py-3">
                        <button type="button" onclick="hideModal('modal-sessao')" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">Cancelar</button>
                        <button type="submit" class="rounded-xl border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Salvar sessao</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-devolutiva" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="hideModal('modal-devolutiva')"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-[#14363a]">Registrar Devolutiva</h3>
                    <button onclick="hideModal('modal-devolutiva')" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <form method="POST" action="{{ route('psicologia.atendimento.devolutiva.store', $atendimento) }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Destinatario *</label>
                            <select name="destinatario" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="familia">Familia</option>
                                <option value="professor">Professor</option>
                                <option value="coordenacao">Coordenacao</option>
                                <option value="direcao">Direcao</option>
                                <option value="funcionario">Funcionario</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data *</label>
                            <input type="date" name="data_devolutiva" value="{{ now()->toDateString() }}" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Resumo da devolutiva</label>
                        <textarea name="resumo_devolutiva" rows="3" placeholder="Resumo do que foi comunicado..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Orientacoes</label>
                        <textarea name="orientacoes" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Encaminhamentos combinados</label>
                        <textarea name="encaminhamentos_combinados" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div class="sticky bottom-0 flex justify-end gap-3 bg-white py-3">
                        <button
                            type="button"
                            onclick="hideModal('modal-devolutiva')"
                            class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                            style="background-color:#fbbf24;border:1px solid #d97706;color:#000;"
                            onmouseover="this.style.backgroundColor='#f59e0b'"
                            onmouseout="this.style.backgroundColor='#fbbf24'"
                        >Cancelar</button>
                        <button
                            type="submit"
                            class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                            style="background-color:#2563eb;border:1px solid #1d4ed8;color:#ffffff;"
                            onmouseover="this.style.backgroundColor='#1d4ed8'"
                            onmouseout="this.style.backgroundColor='#2563eb'"
                        >Salvar devolutiva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-reavaliacao" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="hideModal('modal-reavaliacao')"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-[#14363a]">Reavaliacao do Caso</h3>
                    <button onclick="hideModal('modal-reavaliacao')" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <form method="POST" action="{{ route('psicologia.atendimento.reavaliacao.store', $atendimento) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data da reavaliacao *</label>
                        <input type="date" name="data_reavaliacao" value="{{ now()->toDateString() }}" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Progresso observado</label>
                        <textarea name="progresso_observado" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Dificuldades persistentes</label>
                        <textarea name="dificuldades_persistentes" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Ajuste do plano</label>
                        <textarea name="ajuste_plano" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Decisao *</label>
                        <select name="decisao" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="manter_plano">Manter plano</option>
                            <option value="ajustar_plano">Ajustar plano</option>
                            <option value="suspender">Suspender</option>
                            <option value="encaminhar">Encaminhar</option>
                            <option value="encerrar">Encerrar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Justificativa</label>
                        <textarea name="justificativa" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button
                            type="button"
                            onclick="hideModal('modal-reavaliacao')"
                            class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                            style="background-color:#fbbf24;border:1px solid #d97706;color:#000;"
                            onmouseover="this.style.backgroundColor='#f59e0b'"
                            onmouseout="this.style.backgroundColor='#fbbf24'"
                        >Cancelar</button>
                        <button
                            type="submit"
                            class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                            style="background-color:#2563eb;border:1px solid #1d4ed8;color:#ffffff;"
                            onmouseover="this.style.backgroundColor='#1d4ed8'"
                            onmouseout="this.style.backgroundColor='#2563eb'"
                        >Salvar reavaliacao</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-encerrar" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="hideModal('modal-encerrar')"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-red-700">Encerrar Atendimento</h3>
                    <button onclick="hideModal('modal-encerrar')" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <form method="POST" action="{{ route('psicologia.atendimento.encerrar', $atendimento) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data de encerramento</label>
                        <input type="date" name="data_encerramento" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Motivo do encerramento</label>
                        <select name="motivo_encerramento" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="objetivo_alcancado">Objetivo alcancado</option>
                            <option value="encaminhamento_externo">Encaminhamento externo definitivo</option>
                            <option value="interrupcao_familia">Interrupcao pela familia</option>
                            <option value="evasao">Evasao/abandono</option>
                            <option value="mudanca_escola">Mudanca de escola</option>
                            <option value="decisao_tecnica">Decisao tecnica</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Resumo final</label>
                        <textarea name="resumo_encerramento" rows="4" placeholder="Resumo da evolucao do caso..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Orientacoes finais</label>
                        <textarea name="orientacoes_finais" rows="3" placeholder="Orientacoes para continuidade..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                    </div>
                    <div class="sticky bottom-0 flex justify-end gap-3 bg-white py-3">
                        <button type="button" onclick="hideModal('modal-encerrar')" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">Cancelar</button>
                        <button type="submit" class="rounded-xl border border-red-600 bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Encerrar atendimento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function hideModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</x-psicologia-layout>
