@php
    $statusOptions = $statusOptions ?? ['agendado', 'realizado', 'cancelado', 'faltou', 'encerrado'];
    $tipoPublicoOptions = $tipoPublicoOptions ?? ['aluno', 'professor', 'funcionario', 'responsavel'];
@endphp

<div class="space-y-6">
    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
        <form id="filtros-atendimentos" method="GET" action="{{ $rota }}" class="grid gap-4 md:grid-cols-5">
            <div>
                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Escola</label>
                <select name="escola_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                    <option value="">Todas</option>
                    @foreach ($escolas as $escola)
                        <option value="{{ $escola->id }}" @selected(($filtros['escola_id'] ?? null) == $escola->id)>{{ $escola->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Status</label>
                <select name="status" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                    <option value="">Todos</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status }}" @selected(($filtros['status'] ?? null) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Publico</label>
                <select name="tipo_publico" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                    <option value="">Todos</option>
                    @foreach ($tipoPublicoOptions as $tipo)
                        <option value="{{ $tipo }}" @selected(($filtros['tipo_publico'] ?? null) === $tipo)>{{ ucfirst(str_replace('_', ' ', $tipo)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Paciente</label>
                <input
                    type="text"
                    name="nome"
                    value="{{ $filtros['nome'] ?? '' }}"
                    placeholder="Nome do paciente"
                    class="mt-2 w-full rounded-xl border-slate-300 shadow-sm"
                >
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="w-full rounded-xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">Filtrar</button>
                <a href="{{ $rota }}" class="rounded-xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">Limpar</a>
            </div>
        </form>
    </div>

    <div id="listagem-container" class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Data/Hora</th>
                        <th class="px-6 py-3">Atendido</th>
                        <th class="px-6 py-3">Escola</th>
                        <th class="px-6 py-3">Tipo</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($atendimentos as $atendimento)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <span class="text-slate-700">{{ $atendimento->data_agendada->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route($rotaShow, $atendimento) }}" class="font-semibold text-[#14363a] hover:text-cyan-700">{{ $atendimento->nome_atendido }}</a>
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $atendimento->escola?->nome }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ ucfirst($atendimento->tipo_publico) }} / {{ ucfirst($atendimento->tipo_atendimento) }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ ucfirst(str_replace('_', ' ', $atendimento->status)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($atendimentos->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">{{ $atendimentos->links() }}</div>
        @endif
    </div>
</div>

<script>
    (function() {
        const form = document.getElementById('filtros-atendimentos');
        const nomeInput = form?.querySelector('input[name="nome"]');
        const container = document.getElementById('listagem-container');
        if (!form || !nomeInput || !container) return;

        let debounce;
        nomeInput.addEventListener('input', () => {
            clearTimeout(debounce);
            debounce = setTimeout(fetchResults, 250);
        });

        function fetchResults() {
            const params = new URLSearchParams(new FormData(form)).toString();
            fetch(form.action + '?' + params, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    // extrai somente o novo container para evitar substituir o resto da página
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const updated = temp.querySelector('#listagem-container');
                    if (updated) container.innerHTML = updated.innerHTML;
                })
                .catch(console.error);
        }
    })();
</script>
