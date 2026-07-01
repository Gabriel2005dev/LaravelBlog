@php
    $sidebarItems = [
        ['label' => 'Curtidos', 'href' => route('posts.liked.index'), 'active' => request()->routeIs('posts.liked.index'), 'icon' => 'lucide-heart'],
        ['label' => 'Salvos', 'href' => route('posts.saved.index'), 'active' => request()->routeIs('posts.saved.index'), 'icon' => 'lucide-bookmark'],
        ['label' => 'Atividades', 'href' => route('feed') . '#feed', 'active' => request()->routeIs('feed'), 'icon' => 'lucide-bell'],
    ];
@endphp

<div
    x-data="{ sidebarOpen: {{ request()->routeIs('posts.liked.index') || request()->routeIs('posts.saved.index') || request()->routeIs('feed') ? 'true' : 'false' }} }"
    class="relative z-40"
>
    <aside
        class="fixed inset-y-0 left-0 z-50 flex flex-col border-r border-gray-100 bg-white shadow-lg transition-all duration-300"
        :class="sidebarOpen ? 'w-64' : 'w-20'"
    >
        <div class="flex h-16 items-center justify-center border-b border-gray-100 px-3">
            <a href="{{ route('feed') }}" class="flex items-center justify-center overflow-hidden rounded-full">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
        </div>

        <button
            type="button"
            @click="sidebarOpen = ! sidebarOpen"
            class="mx-auto mt-4 flex h-10 w-10 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
            :title="sidebarOpen ? 'Fechar sidebar' : 'Abrir sidebar'"
        >
            <x-lucide-panel-left-close class="h-5 w-5" x-show="sidebarOpen" />
            <x-lucide-panel-left-open class="h-5 w-5" x-show="!sidebarOpen" />
        </button>

        <nav class="mt-6 flex flex-1 flex-col gap-2 px-3">
            @foreach ($sidebarItems as $item)
                <a
                    href="{{ $item['href'] }}"
                    @click="sidebarOpen = true"
                    class="group flex h-12 items-center rounded-2xl text-sm font-semibold transition {{ $item['active'] ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}"
                    :class="sidebarOpen ? 'justify-start gap-3 px-4' : 'justify-center px-0'"
                    title="{{ $item['label'] }}"
                >
                    <x-dynamic-component :component="$item['icon']" class="h-5 w-5 shrink-0" />
                    <span x-show="sidebarOpen" x-transition.opacity class="truncate">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="border-t border-gray-100 p-3">
            <x-dropdown align="left" width="full">
                <x-slot name="trigger">
                    <button
                        type="button"
                        class="flex h-12 w-full items-center rounded-2xl transition hover:bg-gray-100"
                        :class="sidebarOpen ? 'justify-start gap-3 px-3' : 'justify-center px-0'"
                    >
                        <x-user-avatar :user="Auth::user()" id="navAvatar" size="w-9 h-9" />

                        <span x-show="sidebarOpen" class="min-w-0 text-left">
                            <span class="block truncate text-sm font-semibold text-gray-800">
                                {{ Auth::user()->name }}
                            </span>

                            <span class="block truncate text-xs text-gray-500">
                                {{ Auth::user()->email }}
                            </span>
                        </span>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="border-b px-3 py-3">
                        <p class="truncate text-sm font-semibold">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="truncate text-xs text-gray-500">
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    <x-dropdown-link :href="route('profile.edit')">
                        <div class="flex items-center gap-2">
                            <x-lucide-user class="h-4 w-4" />
                            Perfil
                        </div>
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                        >
                            <div class="flex items-center gap-2">
                                <x-lucide-log-out class="h-4 w-4" />
                                Sair
                            </div>
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </aside>

    <header
        class="fixed left-20 right-0 top-0 z-40 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur transition-all duration-300"
        :class="sidebarOpen ? 'lg:left-64' : 'lg:left-20'"
    >
        <div class="mx-auto flex h-16 max-w-6xl items-center gap-3 px-4">
            <div class="relative flex-1">
                <x-lucide-search
                    class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                />

                <input
                    type="text"
                    placeholder="Pesquisar posts..."
                    class="w-full rounded-full border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <a
                href="{{ route('posts.create') }}"
                class="group inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-br from-[#7B1FF7] via-[#C31BEB] via-[#FF4FA3] to-[#FFD23F] px-4 py-2 text-sm font-semibold text-white shadow-sm"
            >
                <x-lucide-circle-plus class="h-5 w-5 block group-hover:hidden" />
                <x-lucide-badge-plus class="h-5 w-5 hidden group-hover:block" />
                Novo
            </a>
        </div>
    </header>
</div>