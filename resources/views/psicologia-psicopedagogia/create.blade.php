<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="mx-auto max-w-6xl rounded-[1.75rem] border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#14363a]">Novo atendimento sigiloso</h1>
                    <p class="mt-2 text-sm text-slate-500">Cadastre um atendimento para aluno, professor, funcionario, responsavel ou publico coletivo com nivel de sigilo reforcado.</p>
                </div>
                <a href="{{ route('psicologia.dashboard') }}" class="text-sm font-semibold text-cyan-700 hover:text-cyan-800">Voltar ao painel</a>
            </div>

        <form method="POST" action="{{ route('psicologia.store') }}" class="mt-8 space-y-6 pb-2">
            @csrf
            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Escola</label>
                    <select name="escola_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" @selected(old('escola_id', $escolas->count() === 1 ? $escolas->first()->id : null) == $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Profissional responsavel</label>
                    <select name="profissional_responsavel_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Usar profissional vinculado</option>
                        @foreach ($profissionaisPsicossociais as $funcionario)
                            <option value="{{ $funcionario->id }}" @selected(old('profissional_responsavel_id') == $funcionario->id)>{{ $funcionario->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Publico atendido</label>
                    <select name="tipo_publico" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        @foreach (['aluno', 'professor', 'funcionario', 'responsavel', 'coletivo'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo_publico') === $tipo)>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Aluno</label>
                    <select name="aluno_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Selecione quando aplicavel</option>
                        @foreach ($alunos as $aluno)
                            <option value="{{ $aluno->id }}" @selected(old('aluno_id') == $aluno->id)>{{ $aluno->nome_completo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Funcionario / Professor</label>
                    <select name="funcionario_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Selecione quando aplicavel</option>
                        @foreach ($funcionarios as $funcionario)
                            <option value="{{ $funcionario->id }}" @selected(old('funcionario_id') == $funcionario->id)>{{ $funcionario->nome }} - {{ $funcionario->cargo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Responsavel ja cadastrado</label>
                    <select name="responsavel_existente_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        <option value="">Cadastrar novo abaixo</option>
                        @foreach ($responsaveis as $responsavel)
                            <option value="{{ $responsavel->id }}" @selected(old('responsavel_existente_id') == $responsavel->id)>{{ $responsavel->nome }} - {{ ucfirst($responsavel->tipo_vinculo) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 p-5">
                <h2 class="text-lg font-semibold text-[#14363a]">Novo responsavel externo</h2>
                <div class="mt-4 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Nome</label>
                        <input type="text" name="responsavel_nome" value="{{ old('responsavel_nome') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Vinculo</label>
                        <select name="responsavel_tipo_vinculo" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="">Selecione</option>
                            @foreach (['pai', 'mae', 'responsavel', 'outro'] as $vinculo)
                                <option value="{{ $vinculo }}" @selected(old('responsavel_tipo_vinculo') === $vinculo)>{{ ucfirst($vinculo) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">CPF</label>
                        <input type="text" name="responsavel_cpf" value="{{ old('responsavel_cpf') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Telefone</label>
                        <input type="text" name="responsavel_telefone" value="{{ old('responsavel_telefone') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3 xl:grid-cols-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Tipo de atendimento</label>
                    <select name="tipo_atendimento" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        @foreach (['psicologia', 'psicopedagogia', 'psicossocial'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo_atendimento') === $tipo)>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Natureza</label>
                    <select name="natureza" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        @foreach (['agendado', 'retorno', 'emergencial', 'acolhimento'] as $natureza)
                            <option value="{{ $natureza }}" @selected(old('natureza') === $natureza)>{{ ucfirst($natureza) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Status</label>
                    <select name="status" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        @foreach (['agendado', 'realizado', 'cancelado', 'faltou'] as $status)
                            <option value="{{ $status }}" @selected(old('status', 'agendado') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Nivel de sigilo</label>
                    <select name="nivel_sigilo" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        @foreach (['restrito', 'muito_restrito'] as $sigilo)
                            <option value="{{ $sigilo }}" @selected(old('nivel_sigilo', 'muito_restrito') === $sigilo)>{{ ucfirst(str_replace('_', ' ', $sigilo)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Data/Hora agendada</label>
                    <input type="datetime-local" name="data_agendada" value="{{ old('data_agendada') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Data/Hora da realizacao</label>
                    <input type="datetime-local" name="data_realizacao" value="{{ old('data_realizacao') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Local</label>
                    <input type="text" name="local_atendimento" value="{{ old('local_atendimento') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                </div>
            </div>

            <div class="grid gap-5">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Motivo da demanda</label>
                    <textarea name="motivo_demanda" rows="4" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">{{ old('motivo_demanda') }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Resumo sigiloso</label>
                    <textarea name="resumo_sigiloso" rows="4" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">{{ old('resumo_sigiloso') }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Observacoes restritas</label>
                    <textarea name="observacoes_restritas" rows="3" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">{{ old('observacoes_restritas') }}</textarea>
                </div>
            </div>

            <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                <input type="checkbox" name="requer_acompanhamento" value="1" @checked(old('requer_acompanhamento')) class="rounded border-slate-300 text-cyan-700 shadow-sm">
                Requer acompanhamento continuado
            </label>

            <div class="sticky bottom-4 z-10 -mx-2 rounded-3xl border border-cyan-100 bg-slate-50/95 p-3 shadow-[0_18px_40px_rgba(15,23,42,0.12)] backdrop-blur">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700">Ação principal</p>
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-900 bg-slate-950 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/25 transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 sm:w-auto sm:min-w-[220px]">
                        Salvar atendimento
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-psicologia-layout>
