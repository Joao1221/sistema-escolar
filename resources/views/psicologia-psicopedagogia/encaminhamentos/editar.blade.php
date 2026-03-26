<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-[#14363a]">Editar Encaminhamento</h2>
            <p class="mt-2 text-sm text-slate-500">Atualize os dados do encaminhamento para {{ $atendimento->nome_atendido }}.</p>
            
            <form method="POST" action="{{ route('psicologia.encaminhamento.update', $encaminhamento) }}" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipo *</label>
                        <select name="tipo" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="interno" @selected($encaminhamento->tipo === 'interno')>Interno</option>
                            <option value="externo" @selected($encaminhamento->tipo === 'externo')>Externo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Destino *</label>
                        <input type="text" name="destino" value="{{ $encaminhamento->destino }}" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Motivo</label>
                    <textarea name="motivo" rows="3" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $encaminhamento->motivo }}</textarea>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data do encaminhamento</label>
                        <input type="date" name="data_encaminhamento" value="{{ $encaminhamento->data_encaminhamento?->toDateString() }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
                        <select name="status" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="emitido" @selected($encaminhamento->status === 'emitido')>Emitido</option>
                            <option value="em_acompanhamento" @selected($encaminhamento->status === 'em_acompanhamento')>Em acompanhamento</option>
                            <option value="concluido" @selected($encaminhamento->status === 'concluido')>Concluido</option>
                        </select>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Profissional de destino</label>
                        <input type="text" name="profissional_destino" value="{{ $encaminhamento->profissional_destino }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Instituicao de destino</label>
                        <input type="text" name="instituicao_destino" value="{{ $encaminhamento->instituicao_destino }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Orientacoes sigilosas</label>
                    <textarea name="orientacoes_sigilosas" rows="3" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $encaminhamento->orientacoes_sigilosas }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Retorno previsto em</label>
                    <input type="date" name="retorno_previsto_em" value="{{ $encaminhamento->retorno_previsto_em?->toDateString() }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('psicologia.show', $atendimento) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">Cancelar</a>
                    <button
                        type="submit"
                        class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                        style="background-color:#2563eb;border:1px solid #1d4ed8;color:#ffffff;"
                        onmouseover="this.style.backgroundColor='#1d4ed8'"
                        onmouseout="this.style.backgroundColor='#2563eb'"
                    >Atualizar encaminhamento</button>
                </div>
            </form>
        </div>
    </div>
</x-psicologia-layout>