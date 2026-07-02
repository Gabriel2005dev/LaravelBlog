<div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
     <x-dropdown
    align="right"
    position="bottom"
    width="64"
>

    {{-- Trigger --}}
    <x-slot name="trigger">

        <button
            type="button"
            aria-label="Menu do usuário"
            class="rounded-full p-1 transition-all duration-200 hover:scale-105 hover:bg-gray-100"
        >
            <x-user-avatar
                :user="Auth::user()"
                size="w-10 h-10"
            />
        </button>

    </x-slot>

    {{-- Conteúdo --}}
    <x-slot name="content">

        {{-- Usuário --}}
        <div class="flex items-center gap-3 border-b p-3">

            <x-user-avatar
                :user="Auth::user()"
                size="w-10 h-10"
            />

            <div class="min-w-0 flex-1">

                <p class="truncate text-sm font-semibold text-gray-900">
                    {{ Auth::user()->name }}
                </p>

                <p class="truncate text-xs text-gray-500">
                    {{ Auth::user()->email }}
                </p>

            </div>

        </div>

        {{-- Perfil --}}
        <x-dropdown-link :href="route('profile.edit')">

            <div class="flex items-center gap-3">
                <x-lucide-user class="h-4 w-4 text-gray-500" />
                <span>Perfil</span>
            </div>

        </x-dropdown-link>

        {{-- Logout --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <x-dropdown-link
                :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();"
            >

                <div class="flex items-center gap-3 text-red-600">

                    <x-lucide-log-out class="h-4 w-4" />

                    <span>Sair</span>

                </div>

            </x-dropdown-link>

        </form>

    </x-slot>

</x-dropdown>
</div>