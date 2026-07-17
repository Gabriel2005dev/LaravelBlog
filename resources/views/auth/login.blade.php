<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />

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

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

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

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-600"
                >
                <span class="ms-2 text-sm text-gray-600">
                    {{ __('Remember me') }}
                </span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                    href="{{ route('password.request') }}"
                >
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                <span>Entrar</span>
                <x-lucide-log-in class="w-4 h-4" />
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>