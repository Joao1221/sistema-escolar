<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-[#14363a]">Editar Plano de Intervencao</h2>
            <p class="mt-2 text-sm text-slate-500">Atualize os dados do plano de intervencao para {{ $atendimento->nome_atendido }}.</p>
            
            <form method="POST" action="{{ route('psicologia.plano.update', $plano) }}" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Objetivo geral</label>
                    <input type="text" name="objetivo_geral" value="{{ $plano->objetivo_geral }}" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Objetivos especificos</label>
                    <textarea name="objetivos_especificos" rows="3" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $plano->objetivos_especificos }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Estrategias</label>
                    <textarea name="estrategias" rows="3" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $plano->estrategias }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Responsaveis pela execucao</label>
                    <input type="text" name="responsaveis_execucao" value="{{ $plano->responsaveis_execucao }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data inicio</label>
                        <input type="date" name="data_inicio" value="{{ $plano->data_inicio?->toDateString() }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data fim</label>
                        <input type="date" name="data_fim" value="{{ $plano->data_fim?->toDateString() }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
                    <select name="status" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="ativo" @selected($plano->status === 'ativo')>Ativo</option>
                        <option value="em_acompanhamento" @selected($plano->status === 'em_acompanhamento')>Em acompanhamento</option>
                        <option value="concluido" @selected($plano->status === 'concluido')>Concluido</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Observacoes sigilosas</label>
                    <textarea name="observacoes_sigilosas" rows="3" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $plano->observacoes_sigilosas }}</textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('psicologia.show', $atendimento) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">Cancelar</a>
                    <button
                        type="submit"
                        class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                        style="background-color:#2563eb;border:1px solid #1d4ed8;color:#ffffff;"
                        onmouseover="this.style.backgroundColor='#1d4ed8'"
                        onmouseout="this.style.backgroundColor='#2563eb'"
                    >Atualizar plano</button>
                </div>
            </form>
        </div>
    </div>
</x-psicologia-layout>