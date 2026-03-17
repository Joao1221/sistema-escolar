<x-secretaria-escolar-layout>
    <div class="px-8 py-6">
        <div class="mx-auto max-w-6xl rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Novo atendimento sigiloso</h1>
                    <p class="mt-2 text-sm text-slate-500">Cadastre agenda ou atendimento realizado para aluno, professor, funcionario ou responsavel.</p>
                </div>
                <a href="{{ route('secretaria-escolar.psicossocial.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Voltar</a>
            </div>

            <form method="POST" action="{{ route('secretaria-escolar.psicossocial.store') }}" class="mt-8 space-y-6">
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
                            @foreach ($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}" @selected(old('profissional_responsavel_id') == $funcionario->id)>{{ $funcionario->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">Publico atendido</label>
                        <select name="tipo_publico" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                            @foreach (['aluno', 'professor', 'funcionario', 'responsavel'] as $tipo)
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
                    <h2 class="text-lg font-semibold text-slate-900">Novo responsavel externo</h2>
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
                    <input type="checkbox" name="requer_acompanhamento" value="1" @checked(old('requer_acompanhamento')) class="rounded border-slate-300 text-emerald-600 shadow-sm">
                    Requer acompanhamento continuado
                </label>

                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Salvar atendimento</button>
            </form>
        </div>
    </div>
</x-secretaria-escolar-layout>
