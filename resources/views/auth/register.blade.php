<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nome -->
        <div>
            <x-input-label for="name" value="Nome" />

            <div class="relative mt-1">
                <x-lucide-user class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-pink-600" />

                <x-text-input
                    id="name"
                    class="pl-10 block w-full"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                />
            </div>

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>


        <!-- Email -->
        <div class="mt-4">

            <x-input-label for="email" value="Email" />

            <div class="relative mt-1">

                <x-lucide-mail class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-pink-600" />

                <x-text-input
                    id="email"
                    class="pl-10 block w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autocomplete="username"
                />

            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>


        <!-- Senha -->
        <div class="mt-4">

            <x-input-label for="password" value="Senha" />

            <div class="relative mt-1">

                <x-lucide-lock class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-pink-600" />

                <x-text-input
                    id="password"
                    class="pl-10 block w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                />

            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>


        <!-- Confirmar senha -->
        <div class="mt-4">

            <x-input-label 
                for="password_confirmation" 
                value="Confirmar senha"
            />

            <div class="relative mt-1">

                <x-lucide-shield-check class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-pink-600" />

                <x-text-input
                    id="password_confirmation"
                    class="pl-10 block w-full"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                />

            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

        </div>


        <!-- Ações -->
      
            <div class="flex items-center justify-between mt-6 gap-3">

                <a
                    class="text-sm text-gray-600 hover:text-pink-600 transition"
                    href="{{ route('login') }}"
                >
                    Já possui uma conta? Entrar
                </a>


                <x-primary-button>
                    <span>Cadastrar</span>
                </x-primary-button>

            </div>


    </form>

</x-guest-layout>