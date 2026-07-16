<x-guest-layout>

    <x-auth-session-status 
        class="mb-4" 
        :status="session('status')" 
    />


    <form method="POST" action="{{ route('login') }}">

        @csrf


        <!-- Email -->
        <div>

            <x-input-label 
                for="email" 
                value="Email"
            />

            <div class="relative mt-1">

                <x-lucide-mail class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-pink-600" />

                <x-text-input
                    id="email"
                    class="pl-10 block w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                />

            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>



        <!-- Senha -->
        <div class="mt-4">

            <x-input-label 
                for="password"
                value="Senha"
            />


            <div class="relative mt-1">

                <x-lucide-lock class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-pink-600" />

                <x-text-input
                    id="password"
                    class="pl-10 block w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />

            </div>


            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>



        <!-- Lembrar -->
        <div class="mt-4">

            <label for="remember_me" class="inline-flex items-center">

                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-600"
                >

                <span class="ms-2 text-sm text-gray-600">
                    Lembrar de mim
                </span>

            </label>

        </div>



  <!-- Ações -->
<div class="mt-6">

    <div class="flex items-center justify-between gap-3">

        @if (Route::has('password.request'))

            <a
                class="text-sm text-gray-600 hover:text-pink-600 transition"
                href="{{ route('password.request') }}"
            >
                Esqueceu sua senha?
            </a>

        @endif


        <x-primary-button>
            <span>Entrar</span>
        </x-primary-button>

    </div>


    <div class="text-center mt-4">

        <a
            class="text-sm text-gray-600 hover:text-pink-600 transition"
            href="{{ route('register') }}"
        >
            Ainda não possui uma conta? Cadastre-se
        </a>

    </div>

</div>
        </div>


    </form>

</x-guest-layout>