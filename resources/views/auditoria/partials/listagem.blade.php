<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Eventos</p>
        <p class="mt-3 text-3xl font-bold text-slate-900">{{ $metricas['total'] }}</p>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Usuarios</p>
        <p class="mt-3 text-3xl font-bold text-slate-900">{{ $metricas['usuarios'] }}</p>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Modulos</p>
        <p class="mt-3 text-3xl font-bold text-slate-900">{{ $metricas['modulos'] }}</p>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Criticos</p>
        <p class="mt-3 text-3xl font-bold text-slate-900">{{ $metricas['eventos_criticos'] }}</p>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Visualizacoes sensiveis</p>
        <p class="mt-3 text-3xl font-bold text-slate-900">{{ $metricas['visualizacoes_sensiveis'] }}</p>
    </div>
</div>

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">Data</th>
                    <th class="px-4 py-3">Usuario</th>
                    <th class="px-4 py-3">Escola</th>
                    <th class="px-4 py-3">Modulo</th>
                    <th class="px-4 py-3">Acao</th>
                    <th class="px-4 py-3">Registro</th>
                    <th class="px-4 py-3">Sensibilidade</th>
                    <th class="px-4 py-3">Detalhes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($registros as $registro)
                    <tr>
                        <td class="px-4 py-4 text-slate-700">{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-4 text-slate-700">{{ $registro->usuario?->name ?? 'Sistema' }}</td>
                        <td class="px-4 py-4 text-slate-700">{{ $registro->escola?->nome ?? '-' }}</td>
                        <td class="px-4 py-4 text-slate-700">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $registro->modulo)) }}</td>
                        <td class="px-4 py-4 text-slate-700">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $registro->acao)) }}</td>
                        <td class="px-4 py-4 text-slate-700">{{ $registro->tipo_registro }}</td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-2 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] {{ $registro->nivel_sensibilidade === 'sigiloso' ? 'bg-rose-100 text-rose-700' : ($registro->nivel_sensibilidade === 'alto' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $registro->nivel_sensibilidade }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <details class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                <summary class="cursor-pointer text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">Abrir</summary>
                                <div class="mt-3 space-y-3 text-xs text-slate-700">
                                    @if ($registro->valores_antes)
                                        <div>
                                            <p class="font-semibold uppercase tracking-[0.18em] text-slate-500">Antes</p>
                                            <pre class="mt-2 overflow-x-auto whitespace-pre-wrap rounded-xl bg-white p-3">{{ json_encode($registro->valores_antes, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @endif
                                    @if ($registro->valores_depois)
                                        <div>
                                            <p class="font-semibold uppercase tracking-[0.18em] text-slate-500">Depois</p>
                                            <pre class="mt-2 overflow-x-auto whitespace-pre-wrap rounded-xl bg-white p-3">{{ json_encode($registro->valores_depois, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @endif
                                    @if ($registro->contexto)
                                        <div>
                                            <p class="font-semibold uppercase tracking-[0.18em] text-slate-500">Contexto</p>
                                            <pre class="mt-2 overflow-x-auto whitespace-pre-wrap rounded-xl bg-white p-3">{{ json_encode($registro->contexto, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @endif
                                    <div class="grid gap-2 md:grid-cols-2">
                                        <div class="rounded-xl bg-white p-3">IP: {{ $registro->ip ?: '-' }}</div>
                                        <div class="rounded-xl bg-white p-3">User-Agent: {{ \Illuminate\Support\Str::limit($registro->user_agent, 80) ?: '-' }}</div>
                                    </div>
                                </div>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-slate-500">Nenhum registro de auditoria encontrado para os filtros informados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-slate-200 px-4 py-4">
        {{ $registros->links() }}
    </div>
</div>
