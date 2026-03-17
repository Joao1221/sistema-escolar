<form method="GET" action="{{ $rotaIndex }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div>
            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Data inicial</label>
            <input type="date" name="data_inicio" value="{{ $filtros['data_inicio'] ?? '' }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Data final</label>
            <input type="date" name="data_fim" value="{{ $filtros['data_fim'] ?? '' }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Usuario</label>
            <select name="usuario_id" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                <option value="">Todos</option>
                @foreach ($opcoesFiltros['usuarios'] as $usuario)
                    <option value="{{ $usuario->id }}" @selected(($filtros['usuario_id'] ?? null) == $usuario->id)>{{ $usuario->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Escola</label>
            <select name="escola_id" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                <option value="">Todas</option>
                @foreach ($opcoesFiltros['escolas'] as $escola)
                    <option value="{{ $escola->id }}" @selected(($filtros['escola_id'] ?? null) == $escola->id)>{{ $escola->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Modulo</label>
            <select name="modulo" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                <option value="">Todos</option>
                @foreach ($opcoesFiltros['modulos'] as $modulo)
                    <option value="{{ $modulo }}" @selected(($filtros['modulo'] ?? null) == $modulo)>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $modulo)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Acao</label>
            <select name="acao" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                <option value="">Todas</option>
                @foreach ($opcoesFiltros['acoes'] as $acao)
                    <option value="{{ $acao }}" @selected(($filtros['acao'] ?? null) == $acao)>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $acao)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Tipo de registro</label>
            <select name="tipo_registro" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                <option value="">Todos</option>
                @foreach ($opcoesFiltros['tiposRegistro'] as $tipo)
                    <option value="{{ $tipo }}" @selected(($filtros['tipo_registro'] ?? null) == $tipo)>{{ $tipo }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Sensibilidade</label>
            <select name="nivel_sensibilidade" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                <option value="">Todas</option>
                @foreach ($opcoesFiltros['sensibilidades'] as $sensibilidade)
                    <option value="{{ $sensibilidade }}" @selected(($filtros['nivel_sensibilidade'] ?? null) == $sensibilidade)>{{ \Illuminate\Support\Str::title($sensibilidade) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-5 flex flex-wrap gap-3">
        <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
            Filtrar logs
        </button>
        <a href="{{ $rotaIndex }}" class="inline-flex items-center rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Limpar
        </a>
    </div>
</form>
