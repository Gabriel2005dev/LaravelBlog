
 <nav x-data="{ open: false }" class="bg-white shadow">
    <!-- Primary Navigation Menu -->
    <div class="max-w-6xl mx-auto px-4 sm:px-4 lg:px-4">
        <div class="flex justify-between h-16">
            <div class="flex">

            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('feed') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
                    Feed
                </x-nav-link>
            </div>

        </div>

        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="full">
                <x-slot name="trigger">
                      <x-user-avatar :user="Auth::user()" id="navAvatar" />
                </x-slot>

                <x-slot name="content">
                    <div class="px-2 py-2 border-b flex items-center gap-3">

                        <div class="shrink-0">
                            <x-user-avatar :user="Auth::user()" id="navAvatar" size="w-8 h-8" />
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-semibold">
                                {{ Auth::user()->name }}
                            </p>

                            <p class="text-2xs text-gray-500 truncate">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                   </div>

                    <x-dropdown-link :href="route('profile.edit')">
                        <div class="flex items-center gap-2">
                            <x-lucide-user class="w-4 h-4 text-gray-700" />
                            <span>Perfil</span>
                        </div>
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                       <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <div class="flex items-center gap-2">
                                <x-lucide-log-out class="w-4 h-4 text-gray-700" />
                                <span>Sair</span>
                            </div>
                        </x-dropdown-link>

                    </form>

                </x-slot>
            </x-dropdown>
        </div>

        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

    </div>
</div>

<!-- Responsive Navigation Menu -->
<div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">

    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
            Feed
        </x-responsive-nav-link>
    </div>

    <div class="pt-4 pb-1 border-t border-gray-200">

        <div class="px-4">
            <div class="font-medium text-base text-gray-800">
                {{ Auth::user()->name }}
            </div>

            <div class="font-medium text-sm text-gray-500">
                {{ Auth::user()->email }}
            </div>
        </div>

        <div class="mt-3 space-y-1">

            <x-responsive-nav-link :href="route('profile.edit')">
                Perfil
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault();
                    this.closest('form').submit();">
                    Sair
                </x-responsive-nav-link>

            </form>

        </div>

    </div>

</div>
</nav>
