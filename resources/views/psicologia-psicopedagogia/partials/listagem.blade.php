@php
    $statusOptions = $statusOptions ?? ['agendado', 'realizado', 'cancelado', 'faltou'];
    $tipoPublicoOptions = $tipoPublicoOptions ?? ['aluno', 'professor', 'funcionario', 'responsavel'];
@endphp

<div class="space-y-6">
    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
        <form method="GET" action="{{ $rota }}" class="grid gap-4 md:grid-cols-4">
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
            <div class="flex items-end gap-3">
                <button type="submit" class="w-full rounded-xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">Filtrar</button>
                <a href="{{ $rota }}" class="rounded-xl border border-black bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">Limpar</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
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
                                <a href="{{ route($rotaShow, $atendimento) }}" class="font-semibold text-[#14363a] hover:text-cyan-700">{{ $atendimento->data_agendada->format('d/m/Y H:i') }}</a>
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $atendimento->nome_atendido }}</td>
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
