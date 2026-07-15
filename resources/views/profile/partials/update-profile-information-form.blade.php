<section>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('patch')

        <header>

            <h2 class="text-lg font-medium text-gray-900">
                Dados da Conta
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Personalize seu perfil atualizando seu nome, endereço de e-mail e outras informações da conta. Manter seus dados atualizados ajuda a garantir mais segurança e uma experiência completa na plataforma.
            </p>

        </header>

        <div class="mt-8 space-y-7">

            {{-- Nome --}}
            <div>

                <x-input-label
                    for="name"
                    value="Nome Completo"
                    class="font-semibold text-gray-800"
                />

                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-3 block w-full"
                    :value="old('name', $user->name)"
                    required
                    autofocus
                    autocomplete="name"
                />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('name')"
                />

            </div>

            {{-- E-mail --}}
            <div>

                <x-input-label
                    for="email"
                    value="Endereço de E-mail"
                    class="font-semibold text-gray-800"
                />

                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-3 block w-full"
                    :value="old('email', $user->email)"
                    required
                    autocomplete="username"
                />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('email')"
                />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

                    <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">

                        <div class="flex gap-4">

                            <div class="rounded-full bg-amber-100 p-3 h-fit">

                                <x-lucide-triangle-alert
                                    class="w-5 h-5 text-amber-600"
                                />

                            </div>

                            <div class="flex-1">

                                <h3 class="font-semibold text-amber-800">
                                    E-mail não verificado
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-amber-700">
                                    Verifique seu endereço de e-mail para aumentar a segurança da sua conta e desbloquear todos os recursos disponíveis.
                                </p>

                                <button
                                    form="send-verification"
                                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-amber-700"
                                >

                                    <x-lucide-send class="w-4 h-4"/>

                                    Reenviar e-mail de verificação

                                </button>

                            </div>

                        </div>

                    </div>
                                        @if (session('status') === 'verification-link-sent')

                        <div class="mt-5 flex items-center gap-3 rounded-xl bg-green-50 p-4 text-green-700">

                            <x-lucide-circle-check-big
                                class="w-5 h-5 flex-shrink-0"
                            />

                            <span class="text-sm">
                                Um novo e-mail de verificação foi enviado com sucesso.
                            </span>

                        </div>

                    @endif

                @endif

            </div>

            {{-- Rodapé --}}
            <div>

                <div class="flex items-center justify-end gap-4">

                    @if (session('status') === 'profile-updated')

                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition.opacity
                            x-init="setTimeout(() => show = false, 2500)"
                            class="flex items-center text-green-600 mr-auto"
                        >

                            <x-lucide-circle-check-big class="w-5 h-5"/>

                            <span class="text-sm font-medium">
                                Alterações salvas com sucesso.
                            </span>

                        </div>

                    @endif

                    <x-primary-button class="inline-flex items-center">

                        Salvar Alterações

                    </x-primary-button>

                </div>

            </div>

        </div>

    </form>

</section>