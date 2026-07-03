<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Name" />

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

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />

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

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirm Password" />

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

        <!-- Actions -->
        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('login') }}">
                Already registered?
            </a>

            <x-primary-button>
                <span>Cadastre-se</span>
                <x-lucide-send-horizontal class="w-4 h-4" />
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>