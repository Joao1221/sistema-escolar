<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="mx-auto max-w-4xl space-y-6">
        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
                <p class="font-semibold">Nao foi possivel salvar o relatorio.</p>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-700">Relatorio salvo</p>
                    <h2 class="mt-2 text-2xl font-bold text-[#14363a]">{{ $relatorio->titulo }}</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        {{ $atendimento->nome_atendido }} | {{ $atendimento->escola?->nome }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-right">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">Codigo original</p>
                    <p class="mt-1 font-mono text-sm font-bold text-slate-900">{{ $relatorio->created_at?->setTimezone('America/Sao_Paulo')?->format('YmdHis') ?: '-' }}</p>
                    <p class="mt-1 text-xs text-slate-500">Criado em {{ $relatorio->created_at?->setTimezone('America/Sao_Paulo')?->format('d/m/Y H:i') ?: '-' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('psicologia.relatorios_tecnicos.update', $relatorio) }}" class="mt-6 space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="tipo_relatorio" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Tipo do relatorio</label>
                        <select id="tipo_relatorio" name="tipo_relatorio" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="parecer_inicial" @selected(old('tipo_relatorio', $relatorio->tipo_relatorio) === 'parecer_inicial')>Parecer inicial</option>
                            <option value="acompanhamento" @selected(old('tipo_relatorio', $relatorio->tipo_relatorio) === 'acompanhamento')>Acompanhamento</option>
                            <option value="encaminhamento" @selected(old('tipo_relatorio', $relatorio->tipo_relatorio) === 'encaminhamento')>Encaminhamento</option>
                            <option value="sintese" @selected(old('tipo_relatorio', $relatorio->tipo_relatorio) === 'sintese')>Sintese</option>
                        </select>
                    </div>

                    <div>
                        <label for="data_emissao" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Data de emissao</label>
                        <input id="data_emissao" type="date" name="data_emissao" value="{{ old('data_emissao', optional($relatorio->data_emissao)->format('Y-m-d')) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                </div>

                <div>
                    <label for="titulo" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Titulo</label>
                    <input id="titulo" type="text" name="titulo" value="{{ old('titulo', $relatorio->titulo) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                </div>

                <div>
                    <label for="conteudo_sigiloso" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Conteudo tecnico</label>
                    <textarea id="conteudo_sigiloso" name="conteudo_sigiloso" rows="10" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">{{ old('conteudo_sigiloso', $relatorio->conteudo_sigiloso) }}</textarea>
                </div>

                <div>
                    <label for="observacoes_restritas" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Observacoes restritas</label>
                    <textarea id="observacoes_restritas" name="observacoes_restritas" rows="4" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">{{ old('observacoes_restritas', $relatorio->observacoes_restritas) }}</textarea>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <a href="{{ route('psicologia.relatorios_tecnicos.show', $relatorio) }}" class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100">
                        Cancelar
                    </a>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" form="form-excluir-relatorio" title="Excluir relatorio" aria-label="Excluir relatorio" class="inline-flex items-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-rose-700 shadow-sm transition hover:bg-rose-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.5 2a1 1 0 0 0-.894.553L7.382 3H5a1 1 0 1 0 0 2h.293l.842 10.112A2 2 0 0 0 8.128 17h3.744a2 2 0 0 0 1.993-1.888L14.707 5H15a1 1 0 1 0 0-2h-2.382l-.224-.447A1 1 0 0 0 11.5 2h-3Zm1 5a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1Zm3 1a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="sr-only">Excluir relatorio</span>
                        </button>

                        <button type="submit" class="inline-flex items-center rounded-2xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">
                            Salvar alteracoes
                        </button>
                    </div>
                </div>
            </form>

            <form id="form-excluir-relatorio" method="POST" action="{{ route('psicologia.relatorios_tecnicos.destroy', $relatorio) }}" onsubmit="return confirm('Excluir este relatorio tecnico? Esta acao nao pode ser desfeita.');">
                @csrf
                @method('DELETE')
            </form>
        </section>
    </div>
</x-psicologia-layout>
