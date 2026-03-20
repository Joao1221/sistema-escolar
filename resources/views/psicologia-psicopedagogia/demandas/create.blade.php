<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <form method="POST" action="{{ route('psicologia.demandas.store') }}" class="space-y-6" id="form-demanda">
            @csrf

            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Identificacao da demanda</h2>
                
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Escola *</label>
                        <select name="escola_id" required id="escola_id" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="">Selecione</option>
                            @foreach ($escolas as $escola)
                                <option value="{{ $escola->id }}" {{ old('escola_id') == $escola->id ? 'selected' : '' }}>
                                    {{ $escola->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('escola_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Origem da demanda *</label>
                        <select name="origem_demanda" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="">Selecione</option>
                            <option value="coordenacao" {{ old('origem_demanda') == 'coordenacao' ? 'selected' : '' }}>Coordenacao</option>
                            <option value="direcao" {{ old('origem_demanda') == 'direcao' ? 'selected' : '' }}>Direcao</option>
                            <option value="professor" {{ old('origem_demanda') == 'professor' ? 'selected' : '' }}>Professor</option>
                            <option value="familia" {{ old('origem_demanda') == 'familia' ? 'selected' : '' }}>Familia</option>
                            <option value="triagem_interna" {{ old('origem_demanda') == 'triagem_interna' ? 'selected' : '' }}>Triagem interna</option>
                            <option value="demanda_espontanea" {{ old('origem_demanda') == 'demanda_espontanea' ? 'selected' : '' }}>Demanda espontanea</option>
                            <option value="outro" {{ old('origem_demanda') == 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('origem_demanda')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipo de atendimento</label>
                        <select name="tipo_atendimento" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="psicologia" {{ old('tipo_atendimento') == 'psicologia' ? 'selected' : '' }}>Psicologia</option>
                            <option value="psicopedagogia" {{ old('tipo_atendimento') == 'psicopedagogia' ? 'selected' : '' }}>Psicopedagogia</option>
                            <option value="psicossocial" {{ old('tipo_atendimento') == 'psicossocial' ? 'selected' : '' }}>Psicossocial</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Prioridade</label>
                        <select name="prioridade" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="baixa" {{ old('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="media" {{ old('prioridade', 'media') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ old('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ old('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Data da solicitacao</label>
                        <input type="date" name="data_solicitacao" value="{{ old('data_solicitacao', now()->toDateString()) }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Pessoa a ser atendida</h2>
                
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipo de publico *</label>
                        <select name="tipo_publico" required id="tipo_publico" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm" onchange="filtrarPessoas()">
                            <option value="">Selecione</option>
                            <option value="aluno" {{ old('tipo_publico') == 'aluno' ? 'selected' : '' }}>Aluno</option>
                            <option value="professor" {{ old('tipo_publico') == 'professor' ? 'selected' : '' }}>Professor</option>
                            <option value="funcionario" {{ old('tipo_publico') == 'funcionario' ? 'selected' : '' }}>Funcionario</option>
                            <option value="responsavel" {{ old('tipo_publico') == 'responsavel' ? 'selected' : '' }}>Responsavel</option>
                        </select>
                        @error('tipo_publico')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="campo_aluno" style="{{ old('tipo_publico') != 'aluno' ? 'display:none' : '' }}">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Aluno</label>
                        <select name="aluno_id" id="aluno_id" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="">Selecione a escola e o tipo</option>
                        </select>
                    </div>

                    <div id="campo_funcionario" style="{{ !in_array(old('tipo_publico'), ['professor', 'funcionario']) ? 'display:none' : '' }}">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Professor/Funcionario</label>
                        <select name="funcionario_id" id="funcionario_id" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                            <option value="">Selecione a escola e o tipo</option>
                        </select>
                    </div>

                    <div id="campo_responsavel" style="{{ old('tipo_publico') != 'responsavel' ? 'display:none' : '' }}">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Nome do responsavel</label>
                        <input type="text" name="responsavel_nome" value="{{ old('responsavel_nome') }}" placeholder="Nome completo" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>

                    <div id="campo_vinculo" style="{{ old('tipo_publico') != 'responsavel' ? 'display:none' : '' }}">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Vinculo</label>
                        <input type="text" name="responsavel_vinculo" value="{{ old('responsavel_vinculo') }}" placeholder="Ex: Mae, Pai, Avo" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>

                    <div id="campo_telefone" style="{{ old('tipo_publico') != 'responsavel' ? 'display:none' : '' }}">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Telefone</label>
                        <input type="text" name="responsavel_telefone" value="{{ old('responsavel_telefone') }}" placeholder="(00) 00000-0000" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                    </div>
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Motivo da demanda</h2>
                
                <div class="mt-5 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Motivo inicial *</label>
                        <textarea name="motivo_inicial" rows="4" required placeholder="Descreva o motivo da demanda..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ old('motivo_inicial') }}</textarea>
                        @error('motivo_inicial')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Observacoes</label>
                        <textarea name="observacoes" rows="3" placeholder="Informacoes adicionais..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">{{ old('observacoes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('psicologia.demandas.index') }}" class="rounded-xl border border-slate-300 bg-white px-6 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50">
                    Cancelar
                </a>
                <button type="submit" class="rounded-xl border border-emerald-600 bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                    Registrar demanda
                </button>
            </div>
        </form>
    </div>

    <script>
        function filtrarPessoas() {
            var tipo = document.getElementById('tipo_publico').value;
            var escolaId = document.getElementById('escola_id').value;
            
            document.getElementById('campo_aluno').style.display = 'none';
            document.getElementById('campo_funcionario').style.display = 'none';
            document.getElementById('campo_responsavel').style.display = 'none';
            document.getElementById('campo_vinculo').style.display = 'none';
            document.getElementById('campo_telefone').style.display = 'none';
            
            if (tipo === 'aluno') {
                document.getElementById('campo_aluno').style.display = 'block';
                carregarAlunos(escolaId);
            } else if (tipo === 'professor' || tipo === 'funcionario') {
                document.getElementById('campo_funcionario').style.display = 'block';
                carregarFuncionarios(escolaId);
            } else if (tipo === 'responsavel') {
                document.getElementById('campo_responsavel').style.display = 'block';
                document.getElementById('campo_vinculo').style.display = 'block';
                document.getElementById('campo_telefone').style.display = 'block';
            }
        }

        function carregarAlunos(escolaId) {
            var select = document.getElementById('aluno_id');
            select.innerHTML = '<option value="">Carregando...</option>';
            
            if (!escolaId) {
                select.innerHTML = '<option value="">Selecione a escola primeiro</option>';
                return;
            }
            
            fetch('/psicologia-api/dados-escola/' + escolaId, {
                credentials: 'include'
            })
                .then(function(response) {
                    return response.json().then(function(data) {
                        if (!response.ok) {
                            throw new Error(data.error || 'Erro HTTP ' + response.status);
                        }
                        return data;
                    });
                })
                .then(function(data) {
                    select.innerHTML = '<option value="">Selecione</option>';
                    if (data.alunos && data.alunos.length > 0) {
                        data.alunos.forEach(function(aluno) {
                            select.innerHTML += '<option value="' + aluno.id + '">' + aluno.nome_completo + '</option>';
                        });
                    } else {
                        select.innerHTML = '<option value="">Nenhum aluno encontrado</option>';
                    }
                })
                .catch(function(error) {
                    console.error('Erro ao carregar alunos:', error);
                    select.innerHTML = '<option value="">Erro: ' + error.message + '</option>';
                    alert('Erro ao carregar dados: ' + error.message);
                });
        }

        function carregarFuncionarios(escolaId) {
            var select = document.getElementById('funcionario_id');
            select.innerHTML = '<option value="">Carregando...</option>';
            
            if (!escolaId) {
                select.innerHTML = '<option value="">Selecione a escola primeiro</option>';
                return;
            }
            
            fetch('/psicologia-api/dados-escola/' + escolaId, {
                credentials: 'include'
            })
                .then(function(response) {
                    return response.json().then(function(data) {
                        if (!response.ok) {
                            throw new Error(data.error || 'Erro HTTP ' + response.status);
                        }
                        return data;
                    });
                })
                .then(function(data) {
                    select.innerHTML = '<option value="">Selecione</option>';
                    if (data.funcionarios && data.funcionarios.length > 0) {
                        data.funcionarios.forEach(function(func) {
                            select.innerHTML += '<option value="' + func.id + '">' + func.nome + '</option>';
                        });
                    } else {
                        select.innerHTML = '<option value="">Nenhum funcionario encontrado</option>';
                    }
                })
                .catch(function(error) {
                    console.error('Erro ao carregar funcionarios:', error);
                    select.innerHTML = '<option value="">Erro: ' + error.message + '</option>';
                    alert('Erro ao carregar dados: ' + error.message);
                });
        }

        document.getElementById('escola_id').addEventListener('change', function() {
            var tipo = document.getElementById('tipo_publico').value;
            if (tipo === 'aluno') {
                carregarAlunos(this.value);
            } else if (tipo === 'professor' || tipo === 'funcionario') {
                carregarFuncionarios(this.value);
            }
        });

        if (document.getElementById('tipo_publico').value) {
            filtrarPessoas();
        }
    </script>
</x-psicologia-layout>
