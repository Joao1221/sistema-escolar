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
    $permiteEdicaoManual = is_null($usuario->funcionario_id);
    $cargosPsicossociais = \App\Support\CargosPsicossociais::labels();
    $perfilPsicossocial = 'Psicologia/Psicopedagogia';
@endphp

<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Editar Usuario</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $usuario->name }}</p>
        </div>
        <a href="{{ route('secretaria.usuarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('secretaria.usuarios.update', $usuario->id) }}" class="space-y-5 max-w-2xl">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="funcionario_id" :value="__('Funcionario')" />
                <select id="funcionario_id" name="funcionario_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    @if ($permiteEdicaoManual)
                        <option value="">Manter sem funcionario selecionado</option>
                    @endif
                    @foreach ($funcionarios as $funcionario)
                        <option value="{{ $funcionario->id }}" {{ (string) old('funcionario_id', $usuario->funcionario_id) === (string) $funcionario->id ? 'selected' : '' }}>
                            {{ $funcionario->nome }} - {{ $funcionario->cargo }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Ao selecionar um funcionario, nome e e-mail passam a seguir o cadastro dele.</p>
                <x-input-error :messages="$errors->get('funcionario_id')" class="mt-2" />
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="name" :value="__('Nome Completo')" />
                    <x-text-input
                        id="name"
                        class="block mt-1 w-full {{ old('funcionario_id', $usuario->funcionario_id) ? 'bg-gray-50' : '' }}"
                        type="text"
                        name="name"
                        :value="old('name', $usuario->name)"
                        :readonly="(bool) old('funcionario_id', $usuario->funcionario_id)"
                        required
                        autofocus
                    />
                    <p class="mt-1 text-xs text-gray-500">Se houver funcionario selecionado, este campo e preenchido automaticamente.</p>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('E-mail')" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full {{ old('funcionario_id', $usuario->funcionario_id) ? 'bg-gray-50' : '' }}"
                        type="email"
                        name="email"
                        :value="old('email', $usuario->email)"
                        :readonly="(bool) old('funcionario_id', $usuario->funcionario_id)"
                        required
                    />
                    <p class="mt-1 text-xs text-gray-500">Funcionarios vinculados precisam ter e-mail cadastrado para acesso.</p>
                    <div id="funcionario-email-alert" class="mt-2 hidden rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                        Este funcionario nao possui e-mail cadastrado. Atualize o cadastro dele antes de vincular o usuario.
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="password" :value="__('Nova Senha (deixe em branco para manter)')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Nova Senha')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
            </div>

            <div>
                <x-input-label for="role" :value="__('Perfil de Acesso')" />
                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Sem perfil definido</option>
                    @foreach ($perfis as $perfil)
                        <option value="{{ $perfil->id }}" {{ (old('role', $usuario->roles->first()?->id) == $perfil->id) ? 'selected' : '' }}>
                            {{ $perfil->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="escolas" :value="__('Vinculo com Escolas (Segure CTRL para multipla selecao)')" />
                @php $escolasSelecionadas = old('escolas', $usuario->escolas->pluck('id')->toArray()); @endphp
                <p id="escolas-help" class="mt-1 text-xs text-gray-500">Selecione manualmente as escolas apenas para usuarios fora do portal da psicologia.</p>
                <div id="escolas-auto" class="mt-2 hidden rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                    Para psicologo, psicopedagogo ou perfil Psicologia/Psicopedagogia, o acesso sera vinculado automaticamente a todas as escolas ativas da rede.
                </div>
                <div id="escolas-wrapper">
                    <select id="escolas" name="escolas[]" multiple class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm min-h-[100px]">
                        @foreach ($escolas as $escola)
                            <option value="{{ $escola->id }}" {{ in_array($escola->id, $escolasSelecionadas) ? 'selected' : '' }}>
                                {{ $escola->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end border-t pt-5 space-x-4">
                <a href="{{ route('secretaria.usuarios.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-100 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition mr-8">
                    Cancelar
                </a>
                <x-primary-button id="save-usuario-button" style="background-color: #4f46e5;">{{ __('Atualizar Usuario') }}</x-primary-button>
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
                const permiteEdicaoManual = @json($permiteEdicaoManual);
                const nomeManualInicial = nomeInput.value;
                const emailManualInicial = emailInput.value;
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

                const setManualMode = () => {
                    nomeInput.readOnly = false;
                    emailInput.readOnly = false;
                    nomeInput.classList.remove('bg-gray-50');
                    emailInput.classList.remove('bg-gray-50');
                    nomeInput.value = nomeManualInicial;
                    emailInput.value = emailManualInicial;
                    emailAlert.classList.add('hidden');
                    saveButton.disabled = false;
                    saveButton.classList.remove('opacity-60', 'cursor-not-allowed');
                };

                const setLinkedMode = (funcionario) => {
                    const hasEmail = Boolean(funcionario && funcionario.email);

                    nomeInput.readOnly = true;
                    emailInput.readOnly = true;
                    nomeInput.classList.add('bg-gray-50');
                    emailInput.classList.add('bg-gray-50');
                    nomeInput.value = funcionario ? (funcionario.nome || '') : '';
                    emailInput.value = funcionario ? (funcionario.email || '') : '';
                    emailAlert.classList.toggle('hidden', !funcionario || hasEmail);
                    saveButton.disabled = Boolean(funcionario) && !hasEmail;
                    saveButton.classList.toggle('opacity-60', saveButton.disabled);
                    saveButton.classList.toggle('cursor-not-allowed', saveButton.disabled);
                };

                const syncForm = () => {
                    const funcionario = funcionarios[funcionarioSelect.value] || null;

                    if (funcionario) {
                        setLinkedMode(funcionario);
                        atualizarVisibilidadeEscolas();
                        return;
                    }

                    if (permiteEdicaoManual) {
                        setManualMode();
                    }

                    atualizarVisibilidadeEscolas();
                };

                funcionarioSelect.addEventListener('change', syncForm);
                roleSelect.addEventListener('change', atualizarVisibilidadeEscolas);
                syncForm();
            });
        </script>
    @endpush

</x-secretaria-layout>
