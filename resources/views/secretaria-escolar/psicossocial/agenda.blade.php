<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Agenda de atendimentos</h1>
                <p class="mt-2 text-sm text-slate-500">Visualize compromissos sigilosos por escola e tipo de publico.</p>
            </div>
            <a href="{{ route('secretaria-escolar.psicossocial.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Novo atendimento</a>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('secretaria-escolar.psicossocial.agenda') }}" class="grid gap-4 md:grid-cols-4">
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
                        @foreach (['agendado', 'realizado', 'cancelado', 'faltou'] as $status)
                            <option value="{{ $status }}" @selected(($filtros['status'] ?? null) === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Publico</label>
                    <select name="tipo_publico" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Todos</option>
                        @foreach (['aluno', 'professor', 'funcionario', 'responsavel', 'coletivo'] as $tipo)
                            <option value="{{ $tipo }}" @selected(($filtros['tipo_publico'] ?? null) === $tipo)>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filtrar</button>
                    <a href="{{ route('secretaria-escolar.psicossocial.agenda') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Limpar</a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
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
                                    <a href="{{ route('secretaria-escolar.psicossocial.show', $atendimento) }}" class="font-semibold text-slate-900 hover:text-emerald-700">{{ $atendimento->data_agendada->format('d/m/Y H:i') }}</a>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $atendimento->nome_atendido }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $atendimento->escola?->nome }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst($atendimento->tipo_publico) }} / {{ ucfirst($atendimento->tipo_atendimento) }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst($atendimento->status) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Nenhum atendimento encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($atendimentos->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">{{ $atendimentos->links() }}</div>
            @endif
        </div>
    </div>
</x-secretaria-escolar-layout>
