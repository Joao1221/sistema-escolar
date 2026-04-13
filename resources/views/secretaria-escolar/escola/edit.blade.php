<x-secretaria-escolar-layout>
    <style>
        .div-row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }

        .div-col {
            box-sizing: border-box;
            min-width: 0;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            width: 100%;
        }

        .no-spinner::-webkit-outer-spin-button,
        .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .no-spinner {
            -moz-appearance: textfield;
        }

        @media (min-width: 1024px) {
            .div-row {
                flex-wrap: nowrap;
            }

            .col-1 {
                flex: 0 0 8.333333%;
                max-width: 8.333333%;
            }

            .col-2,
            .col-2-alt {
                flex: 0 0 16.666667%;
                max-width: 16.666667%;
            }

            .col-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }

            .col-5 {
                flex: 0 0 41.666667%;
                max-width: 41.666667%;
            }

            .col-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }
    </style>

    <div class="mb-8 px-4 lg:px-0 flex justify-between items-end">
        <div>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-slate-900 font-outfit tracking-tight">Dados da Escola</h1>
            <p class="text-slate-500 mt-1 text-sm lg:text-lg">Atualize as informações cadastrais da sua unidade escolar.</p>
        </div>
        <div>
            <a href="{{ route('secretaria-escolar.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Voltar
            </a>
        </div>
    </div>

    <!-- Mensagens de Feedback -->
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 shadow-sm flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Foram encontrados os seguintes erros:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-10">
        <form method="POST" action="{{ route('secretaria-escolar.dados-escola.update') }}">
            @csrf
            @method('PUT')
            <div class="p-8 space-y-8">
                <!-- Informações do Diretor(a) -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Informações do Diretor(a)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="lg:col-span-2">
                            <label for="nome_gestor" class="block text-sm font-semibold text-slate-700 mb-1">Nome Completo</label>
                            <input type="text" name="nome_gestor" id="nome_gestor" value="{{ old('nome_gestor', $escola->nome_gestor) }}" required maxlength="70" class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                        </div>
                        <div>
                            <label for="cpf_gestor" class="block text-sm font-semibold text-slate-700 mb-1">CPF</label>
                            <input type="text" name="cpf_gestor" id="cpf_gestor" value="{{ old('cpf_gestor', $escola->cpf_gestor) }}" required minlength="11" maxlength="11" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition" placeholder="Apenas números">
                        </div>
                        <div>
                            <label for="ato_posse_diretor" class="block text-sm font-semibold text-slate-700 mb-1">Ato de Posse</label>
                            <input type="text" name="ato_posse_diretor" id="ato_posse_diretor" value="{{ old('ato_posse_diretor', $escola->ato_posse_diretor) }}" required maxlength="30" class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                        </div>
                    </div>
                </div>

                <!-- Informações da Escola -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Informações da Escola</h3>
                    <div class="div-row">
                        <div class="div-col col-2">
                            <label for="inep" class="block text-sm font-semibold text-slate-700 mb-1">Código INEP</label>
                            <input type="text" name="inep" id="inep" value="{{ old('inep', $escola->inep) }}" maxlength="8" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500 focus:ring-0 cursor-not-allowed shadow-none" readonly>
                        </div>
                        <div class="div-col col-5">
                            <label for="nome" class="block text-sm font-semibold text-slate-700 mb-1">Nome da Escola</label>
                            <input type="text" name="nome" id="nome" value="{{ old('nome', $escola->nome) }}" required class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                        </div>
                        <div class="div-col col-1">
                            <label for="qtd_salas" class="block text-sm font-semibold text-red-600 mb-1">* Salas</label>
                            <input type="number" name="qtd_salas" id="qtd_salas" min="0" step="1" value="{{ old('qtd_salas', $escola->qtd_salas) }}" required class="no-spinner w-full text-left rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                        </div>
                        <div class="div-col col-4">
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">E-mail de Contato</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $escola->email) }}" maxlength="70" class="lowercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                        </div>
                    </div>
                </div>

                <!-- Endereço e Contato -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Endereço e Contato</h3>
                    <div class="space-y-6">
                        <div class="div-row">
                            <div class="div-col lg:col-span-7">
                                <label for="endereco" class="block text-sm font-semibold text-slate-700 mb-1">Logradouro / Endereço</label>
                                <input type="text" name="endereco" id="endereco" value="{{ old('endereco', $escola->endereco) }}" maxlength="70" class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                            </div>
                            <div class="div-col lg:col-span-5">
                                <label for="cidade" class="block text-sm font-semibold text-slate-700 mb-1">Cidade</label>
                                <input type="text" name="cidade" id="cidade" value="{{ old('cidade', $escola->cidade) }}" required maxlength="50" class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                            </div>
                        </div>
                        <div class="div-row">
                            <div class="div-col lg:col-span-3">
                                <label for="uf" class="block text-sm font-semibold text-slate-700 mb-1">UF</label>
                                <input type="text" name="uf" id="uf" value="{{ old('uf', $escola->uf) }}" required maxlength="2" class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition text-center">
                            </div>
                            <div class="div-col lg:col-span-4">
                                <label for="cep" class="block text-sm font-semibold text-slate-700 mb-1">CEP</label>
                                <input type="text" name="cep" id="cep" value="{{ old('cep', $escola->cep) }}" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition" placeholder="00000-000">
                            </div>
                            <div class="div-col lg:col-span-5">
                                <label for="telefone" class="block text-sm font-semibold text-slate-700 mb-1">Telefone</label>
                                <input type="text" name="telefone" id="telefone" value="{{ old('telefone', $escola->telefone) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition" placeholder="(00) 00000-0000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documentos da Escola -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Documentos da Escola</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="ato_criacao" class="block text-sm font-semibold text-slate-700 mb-1">Ato de Criação</label>
                            <input type="text" name="ato_criacao" id="ato_criacao" value="{{ old('ato_criacao', $escola->ato_criacao) }}" maxlength="30" class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                        </div>
                        <div>
                            <label for="ato_autoriza" class="block text-sm font-semibold text-slate-700 mb-1">Ato de Autorização</label>
                            <input type="text" name="ato_autoriza" id="ato_autoriza" value="{{ old('ato_autoriza', $escola->ato_autoriza) }}" maxlength="30" class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                        </div>
                        <div>
                            <label for="ato_recon" class="block text-sm font-semibold text-slate-700 mb-1">Ato de Reconhecimento</label>
                            <input type="text" name="ato_recon" id="ato_recon" value="{{ old('ato_recon', $escola->ato_recon) }}" maxlength="30" class="uppercase w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário / Botões -->
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="reset" class="px-5 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 border border-transparent hover:bg-slate-200 rounded-xl transition">
                    Desfazer Limpeza
                </button>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 border border-transparent rounded-xl shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] transition-all transform hover:-translate-y-0.5">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const mascarasCep = document.getElementById('cep');
                if (mascarasCep) {
                    mascarasCep.addEventListener('input', function(e) {
                        let obj = e.target;
                        let value = obj.value.replace(/\D/g, '');
                        if (value.length > 5) {
                            obj.value = value.replace(/^(\d{5})(\d)/, "$1-$2");
                        } else {
                            obj.value = value;
                        }
                    });
                }

                const mascTel = document.getElementById('telefone');
                if(mascTel) {
                    mascTel.addEventListener('input', function(e) {
                        let obj = e.target;
                        let r = obj.value.replace(/\D/g, "");
                        r = r.replace(/^0/, "");
                        if (r.length > 10) {
                            r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
                        } else if (r.length > 5) {
                            r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
                        } else if (r.length > 2) {
                            r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
                        } else {
                            r = r.replace(/^(\d*)/, "($1");
                        }
                        obj.value = r;
                    });
                }
            });
        </script>
    @endpush
</x-secretaria-escolar-layout>
