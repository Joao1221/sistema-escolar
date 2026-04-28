<x-secretaria-escolar-layout>

    <div class="flex justify-between items-center mb-6 px-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 uppercase italic tracking-tight">Editar Matrícula</h1>
            <p class="text-slate-500 mt-1 uppercase decoration-emerald-200 decoration-2">Dados da matrícula e histórico escolar.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('secretaria-escolar.matriculas.show', $matricula) }}" class="flex items-center space-x-2 text-sm text-slate-400 hover:text-emerald-600 font-bold uppercase transition group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Ver Detalhes</span>
            </a>
            <a href="{{ route('secretaria-escolar.matriculas.index') }}" class="flex items-center space-x-2 text-sm text-slate-400 hover:text-emerald-600 font-bold uppercase transition group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Voltar</span>
            </a>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <form method="POST" action="{{ route('secretaria-escolar.matriculas.update', $matricula) }}" x-data="{ tab: 'matricula', seriePretendida: '{{ old('serie_pretendida', $matricula->serie_pretendida ?? '') }}' }">
            @csrf
            @method('PUT')
            
            {{-- Abas --}}
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 pt-4 flex space-x-6 overflow-x-auto no-scrollbar">
                <button type="button" @click="tab = 'matricula'" :class="tab === 'matricula' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Dados da Matrícula</button>
                <button type="button" @click="tab = 'historico'" :class="tab === 'historico' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Histórico Escolar</button>
                <button type="button" @click="tab = 'adicionais'" :class="tab === 'adicionais' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Informações Adicionais</button>
            </div>

            <div class="p-6">
                {{-- ABA 1: DADOS DA MATRÍCULA --}}
                <div x-show="tab === 'matricula'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tipo de Matrícula <span class="text-red-500">*</span></label>
                            <select name="tipo" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                                <option value="regular" {{ $matricula->tipo == 'regular' ? 'selected' : '' }}>Ensino Regular</option>
                                <option value="aee" {{ $matricula->tipo == 'aee' ? 'selected' : '' }}>AEE (Atendimento Especializado)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="ativa" {{ $matricula->status == 'ativa' ? 'selected' : '' }}>Ativa</option>
                                <option value="concluida" {{ $matricula->status == 'concluida' ? 'selected' : '' }}>Concluída</option>
                                <option value="transferida" {{ $matricula->status == 'transferida' ? 'selected' : '' }}>Transferida</option>
                                <option value="cancelada" {{ $matricula->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Aluno <span class="text-red-500">*</span>
                        </h3>
                        <select name="aluno_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                            <option value="">Selecione o aluno...</option>
                            @foreach ($alunos as $aluno)
                                <option value="{{ $aluno->id }}" {{ $matricula->aluno_id == $aluno->id ? 'selected' : '' }}>
                                    {{ $aluno->rgm }} - {{ $aluno->nome_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Ano Letivo <span class="text-red-500">*</span></label>
                            <input type="number" name="ano_letivo" value="{{ $matricula->ano_letivo }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Data da Matrícula</label>
                            <input type="date" name="data_matricula" value="{{ $matricula->data_matricula instanceof \Carbon\Carbon ? $matricula->data_matricula->format('Y-m-d') : $matricula->data_matricula }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Turno</label>
                            <select name="turno" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Selecione...</option>
                                <option value="manha" {{ $matricula->turno == 'manha' ? 'selected' : '' }}>Manhã</option>
                                <option value="tarde" {{ $matricula->turno == 'tarde' ? 'selected' : '' }}>Tarde</option>
                                <option value="noite" {{ $matricula->turno == 'noite' ? 'selected' : '' }}>Noite</option>
                                <option value="integral" {{ $matricula->turno == 'integral' ? 'selected' : '' }}>Integral</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            @php
                                $user = auth()->user();
                                $isGestor = $user && ($user->hasRole('Coordenador Pedagógico') || $user->hasRole('Diretor Escolar') || $user->hasRole('Admin'));
                                $transporteVal = old('transporte', $matricula->transporte);
                                $bolsaVal = old('bolsa_familia', $matricula->bolsa_familia);
                            @endphp
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                Vincular à Turma
                                @if(!$isGestor)<span class="text-xs text-gray-400">(Apenas Coordenador/Diretor)</span>@endif
                            </label>
                            <select name="turma_id" 
                                    x-model="turmaSelecionada" 
                                    @change="seriePretendida = $event.target.options[$event.target.selectedIndex]?.dataset?.serie || ''"
                                    @if(!$isGestor) disabled @endif
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase{{ !$isGestor ? ' bg-gray-100 cursor-not-allowed' : '' }}">
                                <option value="">Decidir depois...</option>
                                @foreach ($turmas as $turma)
                                    <option value="{{ $turma->id }}" data-serie="{{ $turma->serie_etapa }}" {{ $matricula->turma_id == $turma->id ? 'selected' : '' }}>
                                        [{{ strtoupper($turma->turno) }}] {{ $turma->nome }} - {{ $turma->ano_letivo }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Para alterar a turma, utilize a função Enturmar.</p>
                        </div>
                    </div>
                </div>

                {{-- ABA 2: HISTÓRICO ESCOLAR --}}
                <div x-show="tab === 'historico'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m6-4h.01m9 4h.01"/></svg>
                            Série que irá cursar <span class="text-red-500">*</span>
                        </h3>
                        <input type="text" name="serie_pretendida" x-model="seriePretendida" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required placeholder="Ex: 6º Ano EF">
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Escola de Origem
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nome da Escola</label>
                                <input type="text" name="escola_origem" value="{{ $matricula->escola_origem }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">INEP</label>
                                <input type="text" name="escola_inep" value="{{ $matricula->escola_inep }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" maxlength="8">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Rede</label>
                                <select name="rede" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Selecione...</option>
                                    <option value="municipal" {{ $matricula->rede == 'municipal' ? 'selected' : '' }}>Municipal</option>
                                    <option value="publica" {{ $matricula->rede == 'publica' ? 'selected' : '' }}>Pública</option>
                                    <option value="privada" {{ $matricula->rede == 'privada' ? 'selected' : '' }}>Privada</option>
                                    <option value="outra" {{ $matricula->rede == 'outra' ? 'selected' : '' }}>Outra</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cidade/UF</label>
                                <input type="text" name="cidade_uf" value="{{ $matricula->cidade_uf }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" placeholder="Ex: Capela/SE">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Dados do Último Ano Cursado
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Série/Ano</label>
                                <input type="text" name="serie_cursada" value="{{ $matricula->serie_cursada }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" placeholder="Ex: 5º Ano EF">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Último Ano Letivo</label>
                                <input type="number" name="ano_cursado" value="{{ $matricula->ano_cursado }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Ex: 2025">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Situação</label>
                                <select name="situacao" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Selecione...</option>
                                    <option value="transferido" {{ $matricula->situacao == 'transferido' ? 'selected' : '' }}>Transferido</option>
                                    <option value="concluiu" {{ $matricula->situacao == 'concluiu' ? 'selected' : '' }}>Concluiu</option>
                                    <option value="cursando" {{ $matricula->situacao == 'cursando' ? 'selected' : '' }}>Cursando</option>
                                    <option value="desistente" {{ $matricula->situacao == 'desistente' ? 'selected' : '' }}>Desistente</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Data Transferência</label>
                                <input type="date" name="data_transferencia" value="{{ $matricula->data_transferencia instanceof \Carbon\Carbon ? $matricula->data_transferencia->format('Y-m-d') : $matricula->data_transferencia }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="pendencias" id="pendencias" value="1" {{ in_array($matricula->pendencias, [1, '1', true], true) ? 'checked' : '' }} class="w-4 h-4 text-yellow-600 focus:ring-yellow-500 border-yellow-300 rounded">
                            <label for="pendencias" class="text-sm font-semibold text-yellow-800">Existem pendências documentais</label>
                        </div>
                        <div class="mt-3">
                            <label class="block text-xs font-semibold text-yellow-700 uppercase tracking-wider mb-1">Observações / Pendências</label>
                            <textarea name="obs_pendencias" rows="2" class="w-full border-yellow-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-yellow-500 focus:border-yellow-500">{{ $matricula->obs_pendencias }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ABA 3: INFORMAÇÕES ADICIONAIS --}}
                <div x-show="tab === 'adicionais'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            Transporte Escolar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Utiliza transporte público</label>
                                <select name="transporte" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="0" {{ in_array($matricula->transporte, [0, '0', false], true) ? 'selected' : '' }}>Não</option>
                                    <option value="1" {{ in_array($matricula->transporte, [1, '1', true], true) ? 'selected' : '' }}>Sim</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Veículo</label>
                                <select name="transporte_veiculo" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="nao" {{ $matricula->transporte_veiculo == 'nao' ? 'selected' : '' }}>Não Utiliza</option>
                                    <option value="vans" {{ $matricula->transporte_veiculo == 'vans' ? 'selected' : '' }}>Vans/Kombi</option>
                                    <option value="onibus" {{ $matricula->transporte_veiculo == 'onibus' ? 'selected' : '' }}>Ônibus</option>
                                    <option value="bicicleta" {{ $matricula->transporte_veiculo == 'bicicleta' ? 'selected' : '' }}>Bicicleta</option>
                                    <option value="outros" {{ $matricula->transporte_veiculo == 'outros' ? 'selected' : '' }}>Outros</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Bolsa Família
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Recebe Bolsa Família</label>
                                <select name="bolsa_familia" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="0" {{ in_array($matricula->bolsa_familia, [0, '0', false], true) ? 'selected' : '' }}>Não</option>
                                    <option value="1" {{ in_array($matricula->bolsa_familia, [1, '1', true], true) ? 'selected' : '' }}>Sim</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Número do Cartão</label>
                                <input type="text" name="bolsa_cartao" value="{{ $matricula->bolsa_cartao }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" maxlength="11" placeholder="NIS do responsável">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Escolarização em Outro Espaço
                        </h3>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Recebe escolarização em</label>
                            <select name="escolarizacao_outro" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="nao" {{ $matricula->escolarizacao_outro == 'nao' ? 'selected' : '' }}>Não recebe / Escola regular</option>
                                <option value="hospital" {{ $matricula->escolarizacao_outro == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                <option value="domicilio" {{ $matricula->escolarizacao_outro == 'domicilio' ? 'selected' : '' }}>Domicílio</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Observações</label>
                        <textarea name="observacoes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">{{ $matricula->observacoes }}</textarea>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-end border-t pt-6 pb-8 px-6 space-x-4">
                <a href="{{ route('secretaria-escolar.matriculas.show', $matricula) }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-500 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-8 py-3 bg-emerald-600 rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-emerald-700 transition">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>

</x-secretaria-escolar-layout>