<x-secretaria-escolar-layout>

    <div class="mb-6 flex flex-col gap-4 px-0 sm:px-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 uppercase tracking-tight sm:text-3xl">Cadastrar Novo Aluno</h1>
            <p class="text-slate-500 mt-1 uppercase decoration-emerald-200 decoration-2">Ficha completa de matrícula escolar.</p>
        </div>
        <a href="{{ route('secretaria-escolar.alunos.index') }}" class="flex items-center space-x-2 text-sm text-slate-400 hover:text-emerald-600 font-bold uppercase transition group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Voltar</span>
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <form method="POST" action="{{ route('secretaria-escolar.alunos.store') }}" x-data="{ tab: 'dados', modeloCertidao: '{{ old('modelo_certidao', 'novo') }}', defOutras: false, transtornoOutros: false, medicamentoCont: 'nao' }">
            @csrf
            
            {{-- Abas do Formulário --}}
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 pt-4 flex space-x-6 overflow-x-auto no-scrollbar">
                <button type="button" @click="tab = 'dados'" :class="tab === 'dados' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Dados Pessoais</button>
                <button type="button" @click="tab = 'endereco'" :class="tab === 'endereco' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Endereço</button>
                <button type="button" @click="tab = 'documentos'" :class="tab === 'documentos' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Documentos</button>
                <button type="button" @click="tab = 'familia'" :class="tab === 'familia' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Família</button>
                <button type="button" @click="tab = 'saude'" :class="tab === 'saude' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Saúde</button>
                <button type="button" @click="tab = 'autorizacoes'" :class="tab === 'autorizacoes' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500'" class="pb-3 px-1 border-b-2 font-semibold text-xs uppercase tracking-widest transition-all whitespace-nowrap">Autorizações</button>
            </div>

            <div class="p-6">
                {{-- ABA 1: DADOS PESSOAIS --}}
                <div x-show="tab === 'dados'" x-transition class="space-y-6">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Informações Pessoais
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nome Completo <span class="text-red-500">*</span></label>
                                <input type="text" name="nome_completo" value="{{ old('nome_completo') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nome Social</label>
                                <input type="text" name="nome_social" value="{{ old('nome_social') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">E-mail</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">CPF <span class="text-red-500">*</span></label>
                                <input type="text" name="cpf" value="{{ old('cpf') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" placeholder="000.000.000-00" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">NIS (PIS/PASEP)</label>
                                <input type="text" name="nis" value="{{ old('nis') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">INEP</label>
                                <input type="text" name="inep" value="{{ old('inep') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" maxlength="12">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nascimento <span class="text-red-500">*</span></label>
                                <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Sexo <span class="text-red-500">*</span></label>
                                <select name="sexo" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                                    <option value="">Selecione...</option>
                                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                                    <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Outro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Raça/Cor <span class="text-red-500">*</span></label>
                                <select name="raca" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                                    <option value="">Selecione...</option>
                                    <option value="branca" {{ old('raca') == 'branca' ? 'selected' : '' }}>Branca</option>
                                    <option value="negra" {{ old('raca') == 'negra' ? 'selected' : '' }}>Negra</option>
                                    <option value="parda" {{ old('raca') == 'parda' ? 'selected' : '' }}>Parda</option>
                                    <option value="indigena" {{ old('raca') == 'indigena' ? 'selected' : '' }}>Indígena</option>
                                    <option value="nao" {{ old('raca') == 'nao' ? 'selected' : '' }}>Não declarada</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nacionalidade <span class="text-red-500">*</span></label>
                                <input type="text" name="nacionalidade" value="{{ old('nacionalidade') ?? 'BRASILEIRA' }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Naturalidade <span class="text-red-500">*</span></label>
                                <input type="text" name="naturalidade" value="{{ old('naturalidade') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">UF <span class="text-red-500">*</span></label>
                                <input type="text" name="uf_nascimento" value="{{ old('uf_nascimento') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" maxlength="2" required>
                            </div>
                        </div>
</div>
                </div>

                {{-- ABA 2: ENDEREÇO --}}
                <div x-show="tab === 'endereco'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Endereço Residencial
                        </h3>
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                 <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Zona <span class="text-red-500">*</span></label>
                                 <select name="zona" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                                     <option value="">Selecione...</option>
                                     <option value="urbana" {{ old('zona') == 'urbana' ? 'selected' : '' }}>Urbana</option>
                                     <option value="rural" {{ old('zona') == 'rural' ? 'selected' : '' }}>Rural</option>
                                 </select>
                             </div>
                             <div>
                                 <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">CEP <span class="text-red-500">*</span></label>
                                 <input type="text" name="cep" value="{{ old('cep') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                             </div>
                             <div class="md:col-span-2">
                                 <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Logradouro <span class="text-red-500">*</span></label>
                                 <input type="text" name="logradouro" value="{{ old('logradouro') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                             </div>
                             <div>
                                 <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Número <span class="text-red-500">*</span></label>
                                 <input type="text" name="numero" value="{{ old('numero') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                             </div>
                             <div>
                                 <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Complemento</label>
                                 <input type="text" name="complemento" value="{{ old('complemento') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                             </div>
                             <div class="md:col-span-2">
                                 <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Bairro <span class="text-red-500">*</span></label>
                                 <input type="text" name="bairro" value="{{ old('bairro') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                             </div>
                             <div class="grid gap-4 md:col-span-4 md:grid-cols-4">
                                 <div class="md:col-span-3">
                                     <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cidade <span class="text-red-500">*</span></label>
                                     <input type="text" name="cidade" value="{{ old('cidade') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                                 </div>
                                 <div>
                                     <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">UF <span class="text-red-500">*</span></label>
                                     <input type="text" name="uf" value="{{ old('uf') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" maxlength="2" required>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>

                {{-- ABA 3: DOCUMENTOS --}}
                <div x-show="tab === 'documentos'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Documento de Identidade
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">RG</label>
                                <input type="text" name="rg_numero" value="{{ old('rg_numero') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Órgão Emissor</label>
                                <input type="text" name="rg_orgao" value="{{ old('rg_orgao') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">UF</label>
                                <input type="text" name="rg_uf" value="{{ old('rg_uf') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" maxlength="2">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Data Expedição</label>
                                <input type="date" name="rg_data_expedicao" value="{{ old('rg_data_expedicao') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            CERTIDÃO
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">TIPO <span class="text-red-500">*</span></label>
                                <select name="tipo_certidao" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required @change="$el.value === 'nao_possui' ? document.querySelector('[name=certidao_nascimento]').removeAttribute('required') : document.querySelector('[name=certidao_nascimento]').setAttribute('required', 'required')">
                                    <option value="">Selecione...</option>
                                    <option value="nascimento" {{ old('tipo_certidao') == 'nascimento' ? 'selected' : '' }}>Nascimento</option>
                                    <option value="casamento" {{ old('tipo_certidao') == 'casamento' ? 'selected' : '' }}>Casamento</option>
                                    <option value="nao_possui" {{ old('tipo_certidao') == 'nao_possui' ? 'selected' : '' }}>Não Possui</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">MODELO</label>
                                <select name="modelo_certidao" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" x-model="modeloCertidao" @change="modeloCertidao = $event.target.value">
                                    <option value="novo" {{ old('modelo_certidao') == 'novo' || old('modelo_certidao') == '' || !old('modelo_certidao') ? 'selected' : '' }}>Novo</option>
                                    <option value="antigo" {{ old('modelo_certidao') == 'antigo' ? 'selected' : '' }}>Antigo</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">NÚMERO DA MATRÍCULA <span class="text-red-500">*</span></label>
                                <input type="text" name="certidao_nascimento" value="{{ old('certidao_nascimento') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Data Emissão</label>
                                <input type="date" name="certidao_data_emissao" value="{{ old('certidao_data_emissao') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Município do Cartório</label>
                                <input type="text" name="certidao_cartorio_municipio" value="{{ old('certidao_cartorio_municipio') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">UF</label>
                                <input type="text" name="certidao_cartorio_uf" value="{{ old('certidao_cartorio_uf') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" maxlength="2">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nome do Cartório</label>
                                <input type="text" name="certidao_cartorio_nome" value="{{ old('certidao_cartorio_nome') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4" x-show="modeloCertidao === 'antigo'" x-transition>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nº do Termo</label>
                                <input type="text" name="certidao_termo" value="{{ old('certidao_termo') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Folha</label>
                                <input type="text" name="certidao_folha" value="{{ old('certidao_folha') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Livro</label>
                                <input type="text" name="certidao_livro" value="{{ old('certidao_livro') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ABA 4: FAMÍLIA --}}
                <div x-show="tab === 'familia'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Filiação
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nome da Mãe <span class="text-red-500">*</span></label>
                                <input type="text" name="nome_mae" value="{{ old('nome_mae') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Profissão da Mãe</label>
                                <input type="text" name="profissao_mae" value="{{ old('profissao_mae') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nome do Pai</label>
                                <input type="text" name="nome_pai" value="{{ old('nome_pai') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Profissão do Pai</label>
                                <input type="text" name="profissao_pai" value="{{ old('profissao_pai') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Responsável Legal
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nome do Responsável <span class="text-red-500">*</span></label>
                                <input type="text" name="responsavel_nome" value="{{ old('responsavel_nome') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">CPF do Responsável</label>
                                <input type="text" name="responsavel_cpf" value="{{ old('responsavel_cpf') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Telefone</label>
                                <input type="text" name="responsavel_telefone" value="{{ old('responsavel_telefone') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Contatos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Telefone Fixo</label>
                                <input type="text" name="telefone" value="{{ old('telefone') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Celular</label>
                                <input type="text" name="celular" value="{{ old('celular') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ABA 5: SAÚDE --}}
                <div x-show="tab === 'saude'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Condições Especiais
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Deficiência</label>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="fisica" {{ is_array(old('deficiencia')) && in_array('fisica', old('deficiencia')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Física</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="intelectual" {{ is_array(old('deficiencia')) && in_array('intelectual', old('deficiencia')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Intelectual</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="multipla" {{ is_array(old('deficiencia')) && in_array('multipla', old('deficiencia')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Múltipla</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="cegueira" {{ is_array(old('deficiencia')) && in_array('cegueira', old('deficiencia')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Cegueira</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="baixa_visao" {{ is_array(old('deficiencia')) && in_array('baixa_visao', old('deficiencia')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Baixa Visão</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="surdez" {{ is_array(old('deficiencia')) && in_array('surdez', old('deficiencia')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Surdez</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="auditiva" {{ is_array(old('deficiencia')) && in_array('auditiva', old('deficiencia')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Auditiva</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="surdo_cegueira" {{ is_array(old('deficiencia')) && in_array('surdo_cegueira', old('deficiencia')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Surdo/Cegueira</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="deficiencia[]" value="outras" x-model="defOutras" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Outras</span></div>
                                </div>
                                <div x-show="defOutras" class="mt-2"><input type="text" name="deficiencia_outras" value="{{ old('deficiencia_outras') }}" placeholder="Qualifier" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase"></div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Transtorno</label>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2"><input type="checkbox" name="transtorno[]" value="autismo" {{ is_array(old('transtorno')) && in_array('autismo', old('transtorno')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Autismo</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="transtorno[]" value="rett" {{ is_array(old('transtorno')) && in_array('rett', old('transtorno')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Síndrome de Rett</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="transtorno[]" value="asperger" {{ is_array(old('transtorno')) && in_array('asperger', old('transtorno')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Asperger</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="transtorno[]" value="desintegrativo" {{ is_array(old('transtorno')) && in_array('desintegrativo', old('transtorno')) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Desintegrativo</span></div>
                                    <div class="flex items-center gap-2"><input type="checkbox" name="transtorno[]" value="outros" x-model="transtornoOutros" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"><span>Outros</span></div>
                                </div>
                                <div x-show="transtornoOutros" class="mt-2"><input type="text" name="transtorno_outros" value="{{ old('transtorno_outros') }}" placeholder="Qualifier" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase"></div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Altas Habilidades <span class="text-red-500">*</span></label>
                                <select name="altas_habilidades" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500" required>
                                    <option value="nao" selected>Não</option>
                                    <option value="sim" {{ old('altas_habilidades') == 'sim' ? 'selected' : '' }}>Sim</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H9"/></svg>
                            Informações de Saúde
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tipo Sanguíneo</label>
                                <select name="tipo_sanguineo" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Selecione...</option>
                                    <option value="A+" {{ old('tipo_sanguineo') == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('tipo_sanguineo') == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('tipo_sanguineo') == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('tipo_sanguineo') == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('tipo_sanguineo') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('tipo_sanguineo') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('tipo_sanguineo') == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('tipo_sanguineo') == 'O-' ? 'selected' : '' }}>O-</option>
                                </select>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Alergias</label>
                                <textarea name="alergias" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">{{ old('alergias') }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Restrição Alimentar</label>
                                <textarea name="restricoes_alimentares" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">{{ old('restricoes_alimentares') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Medicamento Contínuo</label>
                                <select name="medicamento_continuo" x-model="medicamentoCont" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="nao" selected>Não</option>
                                    <option value="sim" {{ old('medicamento_continuo') == 'sim' ? 'selected' : '' }}>Sim</option>
                                </select>
                            </div>
                            <div x-show="medicamentoCont === 'sim'">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Qual Medicação?</label>
                                <textarea name="medicamentos" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">{{ old('medicamentos') }}</textarea>
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Observações de Saúde</label>
                                <textarea name="obs_saude" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">{{ old('obs_saude') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Contato de Emergência
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nome</label>
                                <input type="text" name="emergencia_nome" value="{{ old('emergencia_nome') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Parentesco</label>
                                <input type="text" name="emergencia_parentesco" value="{{ old('emergencia_parentesco') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Telefone</label>
                                <input type="text" name="emergencia_fone" value="{{ old('emergencia_fone') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ABA 6: AUTORIZAÇÕES --}}
                <div x-show="tab === 'autorizacoes'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Autorizações (LGPD)
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="aut_uso_imagem" id="aut_uso_imagem" value="1" {{ old('aut_uso_imagem') ? 'checked' : 'checked' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="aut_uso_imagem" class="text-sm text-gray-700">Autorizo uso de imagem (fotos/vídeos) para fins pedagógicos</label>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="aut_passeios" id="aut_passeios" value="1" {{ old('aut_passeios') ? 'checked' : 'checked' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="aut_passeios" class="text-sm text-gray-700">Autorizo participação em passeios pedagógicos</label>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="aut_tratamento_dados" id="aut_tratamento_dados" value="1" {{ old('aut_tratamento_dados') ? 'checked' : 'checked' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="aut_tratamento_dados" class="text-sm text-gray-700">Autorizo tratamento de dados pessoais (LGPD)</label>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="aut_saida" id="aut_saida" value="1" {{ old('aut_saida') ? 'checked' : 'checked' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="aut_saida" class="text-sm text-gray-700">Autorizo saída com terceiro autorizado</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-end border-t pt-6 pb-8 px-6 space-x-4">
                <a href="{{ route('secretaria-escolar.alunos.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-500 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-8 py-3 bg-emerald-600 rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-emerald-700 transition">
                    Salvar Aluno
                </button>
            </div>
        </form>
    </div>

</x-secretaria-escolar-layout>
