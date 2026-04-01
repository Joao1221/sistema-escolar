<x-secretaria-escolar-layout>
    <div class="px-8 py-6">
        <div class="mx-auto max-w-5xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Nova demanda psicossocial</h1>
                    <p class="mt-2 text-sm text-slate-500">A escola registra a solicitacao e a equipe psicossocial assume quando iniciar a triagem.</p>
                </div>
                <a href="{{ route('secretaria-escolar.demandas-psicossociais.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Voltar</a>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Revise os campos obrigatorios da demanda.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('secretaria-escolar.demandas-psicossociais.store') }}" class="space-y-6">
                @csrf

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Identificacao da demanda</h2>
                            <p class="mt-1 text-sm text-slate-500">A origem e registrada automaticamente conforme o perfil logado.</p>
                        </div>
                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-emerald-700">
                            {{ $origemDemandaLabel }}
                        </span>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Escola *</label>
                            <select name="escola_id" id="escola_id" required class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="">Selecione</option>
                                @foreach ($escolas as $escola)
                                    <option value="{{ $escola->id }}" @selected(old('escola_id', $escolas->count() === 1 ? $escolas->first()->id : null) == $escola->id)>{{ $escola->nome }}</option>
                                @endforeach
                            </select>
                            @error('escola_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Area tecnica *</label>
                            <select name="tipo_atendimento" required class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                                @foreach (['psicologia', 'psicopedagogia', 'psicossocial'] as $tipoAtendimento)
                                    <option value="{{ $tipoAtendimento }}" @selected(old('tipo_atendimento', 'psicologia') === $tipoAtendimento)>{{ ucfirst($tipoAtendimento) }}</option>
                                @endforeach
                            </select>
                            @error('tipo_atendimento')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Prioridade</label>
                            <select name="prioridade" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                                @foreach (['baixa', 'media', 'alta', 'urgente'] as $prioridade)
                                    <option value="{{ $prioridade }}" @selected(old('prioridade', 'media') === $prioridade)>{{ ucfirst($prioridade) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data da solicitacao</label>
                            <input type="date" name="data_solicitacao" value="{{ old('data_solicitacao', now()->toDateString()) }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Publico a ser atendido</h2>
                    <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipo de publico *</label>
                            <select name="tipo_publico" id="tipo_publico" required class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="">Selecione</option>
                                @foreach (['aluno', 'professor', 'funcionario', 'responsavel', 'coletivo'] as $tipoPublico)
                                    <option value="{{ $tipoPublico }}" @selected(old('tipo_publico') === $tipoPublico)>{{ ucfirst($tipoPublico) }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Use coletivo para turma, serie, turno ou escola inteira, sem selecionar uma pessoa especifica.</p>
                            @error('tipo_publico')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="campo_aluno" style="{{ old('tipo_publico') !== 'aluno' ? 'display:none' : '' }}">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Aluno *</label>
                            <select name="aluno_id" id="aluno_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="">Selecione a escola</option>
                            </select>
                            @error('aluno_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="campo_funcionario" style="{{ !in_array(old('tipo_publico'), ['professor', 'funcionario'], true) ? 'display:none' : '' }}">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Professor / Funcionario *</label>
                            <select name="funcionario_id" id="funcionario_id" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="">Selecione a escola</option>
                            </select>
                            @error('funcionario_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="campo_responsavel" style="{{ old('tipo_publico') !== 'responsavel' ? 'display:none' : '' }}">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Nome do responsavel *</label>
                            <input type="text" name="responsavel_nome" value="{{ old('responsavel_nome') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                            @error('responsavel_nome')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="campo_vinculo" style="{{ old('tipo_publico') !== 'responsavel' ? 'display:none' : '' }}">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Vinculo *</label>
                            <input type="text" name="responsavel_vinculo" value="{{ old('responsavel_vinculo') }}" placeholder="Ex: Mae, Pai, Avo" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                            @error('responsavel_vinculo')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="campo_telefone" style="{{ old('tipo_publico') !== 'responsavel' ? 'display:none' : '' }}">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Telefone</label>
                            <input type="text" name="responsavel_telefone" value="{{ old('responsavel_telefone') }}" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">
                            @error('responsavel_telefone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Contexto da solicitacao</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Motivo inicial *</label>
                            <textarea name="motivo_inicial" rows="5" required class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">{{ old('motivo_inicial') }}</textarea>
                            @error('motivo_inicial')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Observacoes para a equipe</label>
                            <textarea name="observacoes" rows="3" class="mt-2 w-full rounded-xl border-slate-300 shadow-sm">{{ old('observacoes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('secretaria-escolar.demandas-psicossociais.index') }}" class="rounded-xl border border-slate-300 bg-white px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</a>
                    <button type="submit" class="rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white hover:bg-slate-800">Registrar demanda</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const dadosEscolaUrl = "{{ route('secretaria-escolar.demandas-psicossociais.dados-escola', ['escolaId' => '__ESCOLA__']) }}";
            const alunoSelecionado = @json(old('aluno_id'));
            const funcionarioSelecionado = @json(old('funcionario_id'));

            function limparCamposPessoa(tipoAtivo) {
                if (tipoAtivo !== 'aluno') {
                    document.getElementById('aluno_id').value = '';
                }

                if (tipoAtivo !== 'professor' && tipoAtivo !== 'funcionario') {
                    document.getElementById('funcionario_id').value = '';
                }

                if (tipoAtivo !== 'responsavel') {
                    document.querySelector('input[name="responsavel_nome"]').value = '';
                    document.querySelector('input[name="responsavel_vinculo"]').value = '';
                    document.querySelector('input[name="responsavel_telefone"]').value = '';
                }
            }

            function preencherSelect(select, itens, campoValor, campoLabel, selecionado) {
                select.innerHTML = '<option value="">Selecione</option>';

                if (!Array.isArray(itens) || itens.length === 0) {
                    select.innerHTML = '<option value="">Nenhum registro encontrado</option>';
                    return;
                }

                itens.forEach(function(item) {
                    const option = document.createElement('option');
                    option.value = item[campoValor];
                    option.textContent = item[campoLabel];

                    if (String(selecionado || '') === String(item[campoValor])) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });
            }

            function carregarDadosDaEscola(escolaId, callback) {
                if (!escolaId) {
                    callback({ alunos: [], funcionarios: [] });
                    return;
                }

                fetch(dadosEscolaUrl.replace('__ESCOLA__', escolaId), {
                    credentials: 'include'
                })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            if (!response.ok) {
                                throw new Error(data.error || 'Erro ao carregar dados da escola.');
                            }

                            return data;
                        });
                    })
                    .then(callback)
                    .catch(function(error) {
                        console.error(error);
                        callback({ alunos: [], funcionarios: [] });
                    });
            }

            function atualizarCamposPublico() {
                const tipo = document.getElementById('tipo_publico').value;
                const escolaId = document.getElementById('escola_id').value;

                document.getElementById('campo_aluno').style.display = 'none';
                document.getElementById('campo_funcionario').style.display = 'none';
                document.getElementById('campo_responsavel').style.display = 'none';
                document.getElementById('campo_vinculo').style.display = 'none';
                document.getElementById('campo_telefone').style.display = 'none';

                limparCamposPessoa(tipo);

                if (tipo === 'aluno') {
                    document.getElementById('campo_aluno').style.display = 'block';
                    carregarDadosDaEscola(escolaId, function(data) {
                        preencherSelect(document.getElementById('aluno_id'), data.alunos, 'id', 'nome_completo', alunoSelecionado);
                    });
                    return;
                }

                if (tipo === 'professor' || tipo === 'funcionario') {
                    document.getElementById('campo_funcionario').style.display = 'block';
                    carregarDadosDaEscola(escolaId, function(data) {
                        preencherSelect(document.getElementById('funcionario_id'), data.funcionarios, 'id', 'nome', funcionarioSelecionado);
                    });
                    return;
                }

                if (tipo === 'responsavel') {
                    document.getElementById('campo_responsavel').style.display = 'block';
                    document.getElementById('campo_vinculo').style.display = 'block';
                    document.getElementById('campo_telefone').style.display = 'block';
                }
            }

            document.getElementById('tipo_publico').addEventListener('change', atualizarCamposPublico);
            document.getElementById('escola_id').addEventListener('change', atualizarCamposPublico);

            if (document.getElementById('tipo_publico').value) {
                atualizarCamposPublico();
            }
        </script>
    @endpush
</x-secretaria-escolar-layout>
