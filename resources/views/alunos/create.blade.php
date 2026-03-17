<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Cadastrar Novo Aluno</h1>
            <p class="text-sm text-gray-500 mt-1">Insira as informações básicas, endereço, saúde e documentos.</p>
        </div>
        <a href="{{ route('secretaria.alunos.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar para Listagem</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
        <form method="POST" action="{{ route('secretaria.alunos.store') }}" x-data="{ tab: 'pessoal' }">
            @csrf
            
            {{-- Abas do Formulário --}}
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 pt-4 flex space-x-6">
                <button type="button" @click="tab = 'pessoal'" :class="tab === 'pessoal' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'" class="pb-4 px-1 border-b-2 font-bold text-sm transition-all">Dados Pessoais</button>
                <button type="button" @click="tab = 'filiacao'" :class="tab === 'filiacao' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'" class="pb-4 px-1 border-b-2 font-bold text-sm transition-all">Filiação e Responsável</button>
                <button type="button" @click="tab = 'endereco'" :class="tab === 'endereco' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'" class="pb-4 px-1 border-b-2 font-bold text-sm transition-all">Endereço</button>
                <button type="button" @click="tab = 'saude'" :class="tab === 'saude' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'" class="pb-4 px-1 border-b-2 font-bold text-sm transition-all">Saúde</button>
                <button type="button" @click="tab = 'documentos'" :class="tab === 'documentos' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'" class="pb-4 px-1 border-b-2 font-bold text-sm transition-all">Documentação</button>
            </div>

            <div class="p-6">
                {{-- Conteúdo: Dados Pessoais --}}
                <div x-show="tab === 'pessoal'" class="space-y-6 max-w-4xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="nome_completo" :value="__('Nome Completo')" />
                            <x-text-input id="nome_completo" name="nome_completo" type="text" class="mt-1 block w-full uppercase" :value="old('nome_completo')" required autofocus />
                            <x-input-error :messages="$errors->get('nome_completo')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="data_nascimento" :value="__('Data de Nascimento')" />
                            <x-text-input id="data_nascimento" name="data_nascimento" type="date" class="mt-1 block w-full" :value="old('data_nascimento')" required />
                            <x-input-error :messages="$errors->get('data_nascimento')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="sexo" :value="__('Sexo')" />
                            <select id="sexo" name="sexo" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                                <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Outro</option>
                            </select>
                            <x-input-error :messages="$errors->get('sexo')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="cpf" :value="__('CPF do Aluno')" />
                            <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf')" placeholder="000.000.000-00" />
                            <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="nis" :value="__('NIS (PIS/PASEP)')" />
                            <x-text-input id="nis" name="nis" type="text" class="mt-1 block w-full" :value="old('nis')" />
                            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Conteúdo: Filiação --}}
                <div x-show="tab === 'filiacao'" class="space-y-6 max-w-4xl" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="nome_mae" :value="__('Nome da Mãe')" />
                            <x-text-input id="nome_mae" name="nome_mae" type="text" class="mt-1 block w-full uppercase" :value="old('nome_mae')" required />
                            <x-input-error :messages="$errors->get('nome_mae')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="nome_pai" :value="__('Nome do Pai')" />
                            <x-text-input id="nome_pai" name="nome_pai" type="text" class="mt-1 block w-full uppercase" :value="old('nome_pai')" />
                            <x-input-error :messages="$errors->get('nome_pai')" class="mt-2" />
                        </div>
                        <hr class="md:col-span-2 border-gray-100 my-2">
                        <div>
                            <x-input-label for="responsavel_nome" :value="__('Nome do Responsável Legal')" />
                            <x-text-input id="responsavel_nome" name="responsavel_nome" type="text" class="mt-1 block w-full uppercase" :value="old('responsavel_nome')" required />
                            <x-input-error :messages="$errors->get('responsavel_nome')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="responsavel_cpf" :value="__('CPF do Responsável')" />
                            <x-text-input id="responsavel_cpf" name="responsavel_cpf" type="text" class="mt-1 block w-full" :value="old('responsavel_cpf')" required />
                            <x-input-error :messages="$errors->get('responsavel_cpf')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="responsavel_telefone" :value="__('Telefone de Contato')" />
                            <x-text-input id="responsavel_telefone" name="responsavel_telefone" type="text" class="mt-1 block w-full" :value="old('responsavel_telefone')" required />
                            <x-input-error :messages="$errors->get('responsavel_telefone')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Conteúdo: Endereço --}}
                <div x-show="tab === 'endereco'" class="space-y-6 max-w-4xl" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="cep" :value="__('CEP')" />
                            <x-text-input id="cep" name="cep" type="text" class="mt-1 block w-full" :value="old('cep')" required />
                            <x-input-error :messages="$errors->get('cep')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="logradouro" :value="__('Logradouro (Rua, Av.)')" />
                            <x-text-input id="logradouro" name="logradouro" type="text" class="mt-1 block w-full" :value="old('logradouro')" required />
                            <x-input-error :messages="$errors->get('logradouro')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="numero" :value="__('Número')" />
                            <x-text-input id="numero" name="numero" type="text" class="mt-1 block w-full" :value="old('numero')" required />
                            <x-input-error :messages="$errors->get('numero')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="complemento" :value="__('Complemento')" />
                            <x-text-input id="complemento" name="complemento" type="text" class="mt-1 block w-full" :value="old('complemento')" />
                            <x-input-error :messages="$errors->get('complemento')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="bairro" :value="__('Bairro')" />
                            <x-text-input id="bairro" name="bairro" type="text" class="mt-1 block w-full" :value="old('bairro')" required />
                            <x-input-error :messages="$errors->get('bairro')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="cidade" :value="__('Cidade')" />
                            <x-text-input id="cidade" name="cidade" type="text" class="mt-1 block w-full" :value="old('cidade')" required />
                            <x-input-error :messages="$errors->get('cidade')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="uf" :value="__('UF')" />
                            <x-text-input id="uf" name="uf" type="text" class="mt-1 block w-full uppercase" :value="old('uf')" required maxlength="2" />
                            <x-input-error :messages="$errors->get('uf')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Conteúdo: Saúde --}}
                <div x-show="tab === 'saude'" class="space-y-6 max-w-4xl" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="alergias" :value="__('Alergias')" />
                            <textarea id="alergias" name="alergias" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('alergias') }}</textarea>
                            <x-input-error :messages="$errors->get('alergias')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="medicamentos" :value="__('Medicamentos de Uso Contínuo')" />
                            <textarea id="medicamentos" name="medicamentos" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('medicamentos') }}</textarea>
                            <x-input-error :messages="$errors->get('medicamentos')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="restricoes_alimentares" :value="__('Restrições Alimentares')" />
                            <textarea id="restricoes_alimentares" name="restricoes_alimentares" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('restricoes_alimentares') }}</textarea>
                            <x-input-error :messages="$errors->get('restricoes_alimentares')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="obs_saude" :value="__('Observações Gerais de Saúde')" />
                            <textarea id="obs_saude" name="obs_saude" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('obs_saude') }}</textarea>
                            <x-input-error :messages="$errors->get('obs_saude')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Conteúdo: Documentos --}}
                <div x-show="tab === 'documentos'" class="space-y-6 max-w-4xl" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="certidao_nascimento" :value="__('Certidão de Nascimento (Nº/Termo/Livro)')" />
                            <x-text-input id="certidao_nascimento" name="certidao_nascimento" type="text" class="mt-1 block w-full" :value="old('certidao_nascimento')" />
                            <x-input-error :messages="$errors->get('certidao_nascimento')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="rg_numero" :value="__('RG (Número)')" />
                            <x-text-input id="rg_numero" name="rg_numero" type="text" class="mt-1 block w-full" :value="old('rg_numero')" />
                            <x-input-error :messages="$errors->get('rg_numero')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="rg_orgao" :value="__('Órgão Emissor / UF')" />
                            <x-text-input id="rg_orgao" name="rg_orgao" type="text" class="mt-1 block w-full uppercase" :value="old('rg_orgao')" />
                            <x-input-error :messages="$errors->get('rg_orgao')" class="mt-2" />
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-end border-t pt-5 pb-6 px-6 space-x-4">
                <a href="{{ route('secretaria.alunos.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition mr-8">
                    Cancelar
                </a>
                <x-primary-button style="background-color: #4f46e5;">{{ __('Salvar Aluno') }}</x-primary-button>
            </div>
        </form>
    </div>

</x-secretaria-layout>
