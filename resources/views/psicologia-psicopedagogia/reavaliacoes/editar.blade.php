<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-[#14363a]">Editar Reavaliacao</h2>
            <p class="mt-2 text-sm text-slate-500">Atualize os dados da reavaliacao para {{ $atendimento->nome_atendido }}.</p>
            
            <form method="POST" action="{{ route('psicologia.reavaliacao.update', $reavaliacao) }}" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data da reavaliacao *</label>
                    <input type="date" name="data_reavaliacao" value="{{ $reavaliacao->data_reavaliacao->toDateString() }}" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Progresso observado</label>
                    <textarea name="progresso_observado" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $reavaliacao->progresso_observado }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Dificuldades persistentes</label>
                    <textarea name="dificuldades_persistentes" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $reavaliacao->dificuldades_persistentes }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Ajuste do plano</label>
                    <textarea name="ajuste_plano" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $reavaliacao->ajuste_plano }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Decisao *</label>
                    <select name="decisao" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="manter_plano" @selected($reavaliacao->decisao === 'manter_plano')>Manter plano</option>
                        <option value="ajustar_plano" @selected($reavaliacao->decisao === 'ajustar_plano')>Ajustar plano</option>
                        <option value="suspender" @selected($reavaliacao->decisao === 'suspender')>Suspender</option>
                        <option value="encaminhar" @selected($reavaliacao->decisao === 'encaminhar')>Encaminhar</option>
                        <option value="encerrar" @selected($reavaliacao->decisao === 'encerrar')>Encerrar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Justificativa</label>
                    <textarea name="justificativa" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $reavaliacao->justificativa }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Proxima reavaliacao</label>
                    <input type="date" name="proxima_reavaliacao" value="{{ $reavaliacao->proxima_reavaliacao?->toDateString() }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('psicologia.show', $atendimento) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">Cancelar</a>
                    <button
                        type="submit"
                        class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                        style="background-color:#2563eb;border:1px solid #1d4ed8;color:#ffffff;"
                        onmouseover="this.style.backgroundColor='#1d4ed8'"
                        onmouseout="this.style.backgroundColor='#2563eb'"
                    >Atualizar reavaliacao</button>
                </div>
            </form>
        </div>
    </div>
</x-psicologia-layout>
