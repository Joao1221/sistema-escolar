<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-[#14363a]">Editar Devolutiva</h2>
            <p class="mt-2 text-sm text-slate-500">Atualize os dados da devolutiva para {{ $atendimento->nome_atendido }}.</p>
            
            <form method="POST" action="{{ route('psicologia.devolutiva.update', $devolutiva) }}" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Destinatario *</label>
                        <select name="destinatario" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="familia" @selected($devolutiva->destinatario === 'familia')>Familia</option>
                            <option value="professor" @selected($devolutiva->destinatario === 'professor')>Professor</option>
                            <option value="coordenacao" @selected($devolutiva->destinatario === 'coordenacao')>Coordenacao</option>
                            <option value="direcao" @selected($devolutiva->destinatario === 'direcao')>Direcao</option>
                            <option value="funcionario" @selected($devolutiva->destinatario === 'funcionario')>Funcionario</option>
                            <option value="outro" @selected($devolutiva->destinatario === 'outro')>Outro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data *</label>
                        <input type="date" name="data_devolutiva" value="{{ $devolutiva->data_devolutiva->toDateString() }}" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Resumo da devolutiva</label>
                    <textarea name="resumo_devolutiva" rows="3" placeholder="Resumo do que foi comunicado..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $devolutiva->resumo_devolutiva }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Orientacoes</label>
                    <textarea name="orientacoes" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $devolutiva->orientacoes }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Encaminhamentos combinados</label>
                    <textarea name="encaminhamentos_combinados" rows="2" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ $devolutiva->encaminhamentos_combinados }}</textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('psicologia.show', $atendimento) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">Cancelar</a>
                    <button
                        type="submit"
                        class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm transition"
                        style="background-color:#2563eb;border:1px solid #1d4ed8;color:#ffffff;"
                        onmouseover="this.style.backgroundColor='#1d4ed8'"
                        onmouseout="this.style.backgroundColor='#2563eb'"
                    >Atualizar devolutiva</button>
                </div>
            </form>
        </div>
    </div>
</x-psicologia-layout>
