<x-secretaria-layout>

    <div class="flex justify-between items-start mb-6">
        <div class="flex items-center space-x-4">
            <div class="bg-indigo-100 p-4 rounded-2xl text-indigo-600">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 uppercase">{{ $aluno->nome_completo }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">RGM: {{ $aluno->rgm }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $aluno->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $aluno->ativo ? 'ATIVO' : 'INATIVO' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('secretaria.alunos.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                Voltar
            </a>
            <div class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg font-semibold text-xs uppercase tracking-widest cursor-not-allowed italic" title="A edição é feita apenas no Portal Escolar">
                Somente Leitura
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Coluna 1: Informações Pessoais e Contato --}}
        <div class="col-span-1 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Identificação do Aluno</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Nome Completo</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->nome_completo }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Nascimento</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $aluno->data_nascimento->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Idade</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $aluno->idade }} anos</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Sexo</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $aluno->sexo == 'M' ? 'Masculino' : ($aluno->sexo == 'F' ? 'Feminino' : 'Outro') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">CPF</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $aluno->cpf ?: 'Não informado' }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">NIS</p>
                        <p class="text-sm text-gray-800 font-medium">{{ $aluno->nis ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Família / Responsável</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Mãe</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->nome_mae }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Responsável Legal</p>
                        <p class="text-sm text-gray-800 font-bold uppercase">{{ $aluno->responsavel_nome }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Contato</p>
                        <p class="text-sm text-gray-800 font-medium">{{ $aluno->responsavel_telefone }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coluna 2: Endereço e Saúde --}}
        <div class="col-span-1 md:col-span-2 space-y-6">
            
            {{-- Endereço --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Localização</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Endereço</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->logradouro }}, {{ $aluno->numero }}</p>
                        <p class="text-xs text-gray-500 uppercase">{{ $aluno->bairro }} - {{ $aluno->cidade }}/{{ $aluno->uf }}</p>
                    </div>
                </div>
            </div>

            {{-- Saúde --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Informações Críticas de Saúde</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                        <p class="text-[10px] text-indigo-500 font-bold uppercase mb-1 underline">Alergias e Restrições</p>
                        <p class="text-xs text-indigo-900 leading-relaxed font-medium uppercase">
                            {{ $aluno->alergias ?: 'Sem registros' }} / {{ $aluno->restricoes_alimentares ?: 'Sem restrições' }}
                        </p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <p class="text-[10px] text-slate-500 font-bold uppercase mb-1">Observações Médicas</p>
                        <p class="text-xs text-slate-800 leading-relaxed font-medium uppercase">
                            {{ $aluno->obs_saude ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Área Estatística (Placeholder) --}}
            <div class="bg-indigo-900 text-white rounded-2xl p-8 shadow-sm">
                <h4 class="text-indigo-300 text-[10px] font-bold uppercase tracking-widest mb-4">Visão Educacional</h4>
                <div class="flex items-center space-x-4 opacity-60">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium">Os dados de matrícula, frequência e histórico pedagógico consolidados estarão disponíveis em breve.</p>
                </div>
            </div>

        </div>
    </div>

</x-secretaria-layout>
