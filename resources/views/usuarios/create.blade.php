@php
    $funcionariosData = $funcionarios
        ->mapWithKeys(fn ($funcionario) => [
            (string) $funcionario->id => [
                'nome' => $funcionario->nome,
                'email' => $funcionario->email,
                'cargo' => $funcionario->cargo,
            ],
        ])
        ->toArray();
    $perfisData = $perfis
        ->mapWithKeys(fn ($perfil) => [
            (string) $perfil->id => [
                'name' => $perfil->name,
            ],
        ])
        ->toArray();
    $semFuncionariosDisponiveis = $funcionarios->isEmpty();
    $cargosPsicossociais = \App\Support\CargosPsicossociais::labels();
    $perfilPsicossocial = 'Psicologia/Psicopedagogia';
@endphp

<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Novo Usuario</h1>
            <p class="text-sm text-gray-500 mt-1">Criar conta de acesso apenas para funcionarios ja cadastrados</p>
        </div>
        <a href="{{ route('secretaria.usuarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    @if ($semFuncionariosDisponiveis)
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Nenhum funcionario ativo e sem usuario vinculado esta disponivel para cadastro.
            <a href="{{ route('secretaria.funcionarios.create') }}" class="font-semibold underline">Cadastrar funcionario</a>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('secretaria.usuarios.store') }}" class="space-y-5 max-w-2xl">
            @csrf

            <div>
                <x-input-label for="funcionario_id" :value="__('Funcionario')" />
                <select
                    id="funcionario_id"
                    name="funcionario_id"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required
                    @disabled($semFuncionariosDisponiveis)
                >
                    <option value="">Selecione um funcionario cadastrado</option>
                    @foreach ($funcionarios as $funcionario)
                        <option value="{{ $funcionario->id }}" {{ (string) old('funcionario_id') === (string) $funcionario->id ? 'selected' : '' }}>
                            {{ $funcionario->nome }} - {{ $funcionario->cargo }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Somente funcionarios ativos e sem usuario vinculado aparecem nesta lista.</p>
                <x-input-error :messages="$errors->get('funcionario_id')" class="mt-2" />
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="name" :value="__('Nome Completo')" />
                    <x-text-input id="name" class="block mt-1 w-full bg-gray-50" type="text" name="name" :value="old('name')" readonly />
                    <p class="mt-1 text-xs text-gray-500">Carregado automaticamente a partir do cadastro do funcionario.</p>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('E-mail')" />
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-50" type="email" name="email" :value="old('email')" readonly />
                    <p class="mt-1 text-xs text-gray-500">O e-mail precisa existir no cadastro do funcionario para liberar o acesso.</p>
                    <div id="funcionario-email-alert" class="mt-2 hidden rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                        Este funcionario nao possui e-mail cadastrado. Atualize o cadastro do funcionario antes de criar o usuario.
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="password" :value="__('Senha')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <div>
                <x-input-label for="role" :value="__('Perfil de Acesso')" />
                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Sem perfil definido</option>
                    @foreach ($perfis as $perfil)
                        <option value="{{ $perfil->id }}" {{ old('role') == $perfil->id ? 'selected' : '' }}>{{ $perfil->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="escolas" :value="__('Vinculo com Escolas (Segure CTRL para multipla selecao)')" />
                <p id="escolas-help" class="mt-1 text-xs text-gray-500">Selecione manualmente as escolas apenas para usuarios que nao pertencem ao portal da psicologia.</p>
                <div id="escolas-auto" class="mt-2 hidden rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                    Para psicologo, psicopedagogo ou perfil Psicologia/Psicopedagogia, o acesso sera vinculado automaticamente a todas as escolas ativas da rede.
                </div>
                <div id="escolas-wrapper">
                    <select id="escolas" name="escolas[]" multiple class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm min-h-[100px]">
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" {{ (is_array(old('escolas')) && in_array($escola->id, old('escolas'))) ? 'selected' : '' }}>
                                {{ $escola->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="ativo" class="inline-flex items-center">
                    <input id="ativo" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="ativo" {{ old('ativo', true) ? 'checked' : '' }}>
                    <span class="ms-2 text-sm text-gray-600">{{ __('Usuario Ativo') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end border-t pt-5 space-x-4">
                <a href="{{ route('secretaria.usuarios.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition mr-8">
                    Cancelar
                </a>
                <x-primary-button id="save-usuario-button" style="background-color: #4f46e5;" :disabled="$semFuncionariosDisponiveis">{{ __('Salvar Usuario') }}</x-primary-button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const funcionarios = @json($funcionariosData, JSON_UNESCAPED_UNICODE);
                const funcionarioSelect = document.getElementById('funcionario_id');
                const roleSelect = document.getElementById('role');
                const nomeInput = document.getElementById('name');
                const emailInput = document.getElementById('email');
                const emailAlert = document.getElementById('funcionario-email-alert');
                const saveButton = document.getElementById('save-usuario-button');
                const escolasSelect = document.getElementById('escolas');
                const escolasWrapper = document.getElementById('escolas-wrapper');
                const escolasHelp = document.getElementById('escolas-help');
                const escolasAuto = document.getElementById('escolas-auto');
                const perfis = @json($perfisData, JSON_UNESCAPED_UNICODE);
                const cargosPsicossociais = @json($cargosPsicossociais, JSON_UNESCAPED_UNICODE);
                const perfilPsicossocial = @json($perfilPsicossocial);

                if (!funcionarioSelect || !roleSelect || !nomeInput || !emailInput || !saveButton || !escolasSelect) {
                    return;
                }

                const normalizarTexto = (valor) => (valor || '')
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase();

                const atualizarVisibilidadeEscolas = () => {
                    const funcionario = funcionarios[funcionarioSelect.value] || null;
                    const perfil = perfis[roleSelect.value] || null;
                    const acessoAutomatico =
                        cargosPsicossociais.map(normalizarTexto).includes(normalizarTexto(funcionario ? funcionario.cargo : ''))
                        || normalizarTexto(perfil ? perfil.name : '') === normalizarTexto(perfilPsicossocial);

                    escolasWrapper.classList.toggle('hidden', acessoAutomatico);
                    escolasHelp.classList.toggle('hidden', acessoAutomatico);
                    escolasAuto.classList.toggle('hidden', !acessoAutomatico);
                    escolasSelect.disabled = acessoAutomatico;
                };

                const updateFormFromFuncionario = () => {
                    const funcionario = funcionarios[funcionarioSelect.value] || null;
                    const hasEmail = Boolean(funcionario && funcionario.email);

                    nomeInput.value = funcionario ? (funcionario.nome || '') : '';
                    emailInput.value = funcionario ? (funcionario.email || '') : '';
                    emailAlert.classList.toggle('hidden', !funcionario || hasEmail);

                    saveButton.disabled = !funcionario || !hasEmail;
                    saveButton.classList.toggle('opacity-60', saveButton.disabled);
                    saveButton.classList.toggle('cursor-not-allowed', saveButton.disabled);
                    atualizarVisibilidadeEscolas();
                };

                funcionarioSelect.addEventListener('change', updateFormFromFuncionario);
                roleSelect.addEventListener('change', atualizarVisibilidadeEscolas);
                updateFormFromFuncionario();
            });
        </script>
    @endpush

</x-secretaria-layout>
