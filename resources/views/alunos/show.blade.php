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
                    <span class="text-sm font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">RGM: {{ $aluno->rgm }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $aluno->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $aluno->ativo ? 'ATIVO' : 'INATIVO' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('secretaria.alunos.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                Voltar
            </a>
            @can('editar aluno')
            <a href="{{ route('secretaria.alunos.edit', $aluno) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700 transition" style="background-color: #4f46e5;">
                Editar Perfil
            </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Coluna 1: Informações Pessoais e Contato --}}
        <div class="col-span-1 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Dados Pessoais</h3>
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
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Família / Responsável</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Mãe</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->nome_mae }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Pai</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->nome_pai ?: '-' }}</p>
                    </div>
                    <hr class="border-gray-50">
                    <div>
                        <p class="text-[10px] text-indigo-400 font-bold uppercase">Responsável Legal</p>
                        <p class="text-sm text-gray-800 font-bold uppercase">{{ $aluno->responsavel_nome }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Telefone do Responsável</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $aluno->responsavel_telefone }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">CPF do Responsável</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $aluno->responsavel_cpf }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coluna 2: Endereço e Saúde --}}
        <div class="col-span-1 md:col-span-2 space-y-6">
            
            {{-- Endereço --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Endereço Residencial</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Logradouro</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->logradouro }}, {{ $aluno->numero }}</p>
                        <p class="text-xs text-gray-500">{{ $aluno->complemento ?: '' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Bairro</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->bairro }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Cidade / UF</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->cidade }} - {{ $aluno->uf }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">CEP</p>
                        <p class="text-sm text-gray-800 font-medium">{{ $aluno->cep }}</p>
                    </div>
                </div>
            </div>

            {{-- Saúde --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Ficha Médica / Saúde</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-red-50 p-3 rounded-xl border border-red-100">
                        <p class="text-[10px] text-red-400 font-bold uppercase mb-1">Alergias</p>
                        <p class="text-xs text-red-900 leading-relaxed font-medium">
                            {{ $aluno->alergias ?: 'Nenhuma alergia registrada.' }}
                        </p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                        <p class="text-[10px] text-blue-400 font-bold uppercase mb-1">Medicamentos</p>
                        <p class="text-xs text-blue-900 leading-relaxed font-medium">
                            {{ $aluno->medicamentos ?: 'Nenhum medicamento de uso contínuo.' }}
                        </p>
                    </div>
                    <div class="bg-orange-50 p-3 rounded-xl border border-orange-100">
                        <p class="text-[10px] text-orange-400 font-bold uppercase mb-1">Restrições Alimentares</p>
                        <p class="text-xs text-orange-900 leading-relaxed font-medium">
                            {{ $aluno->restricoes_alimentares ?: 'Sem restrições alimentares registradas.' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Observações Gerais</p>
                        <p class="text-xs text-gray-900 leading-relaxed font-medium">
                            {{ $aluno->obs_saude ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Documentos Internos --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Documentação</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Certidão de Nascimento</p>
                        <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->certidao_nascimento ?: 'Não informada' }}</p>
                    </div>
                    <div class="flex space-x-6">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">RG</p>
                            <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->rg_numero ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Órgão Emissor</p>
                            <p class="text-sm text-gray-800 font-medium uppercase">{{ $aluno->rg_orgao ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-secretaria-layout>
