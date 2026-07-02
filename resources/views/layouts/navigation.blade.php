@php
$navItems = [
    [
        'label' => 'Home',
        'href' => route('feed'),
        'active' => request()->routeIs('feed'),
        'icon' => 'lucide-house',
    ],
    [
        'label' => 'Curtidos',
        'href' => route('posts.liked.index'),
        'active' => request()->routeIs('posts.liked.index'),
        'icon' => 'lucide-heart',
    ],
    [
        'label' => 'Salvos',
        'href' => route('posts.saved.index'),
        'active' => request()->routeIs('posts.saved.index'),
        'icon' => 'lucide-bookmark',
    ],
    [
        'label' => 'Atividades',
        'href' => route('feed') . '#feed',
        'active' => false,
        'icon' => 'lucide-bell',
    ],
];
@endphp

<header class="fixed top-0 left-0 right-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between">

        {{-- Logo + Navegação --}}
        <div class="flex items-center gap-8">

            <a href="{{ route('feed') }}" class="flex items-center gap-2">
                <x-application-logo class="h-9 w-auto fill-current text-gray-800" />
            </a>

            <nav class="hidden lg:flex items-center gap-1">

                @foreach($navItems as $item)

                    <a
                        href="{{ $item['href'] }}"
                        title="{{ $item['label'] }}"
                        class="flex h-11 w-11 items-center justify-center rounded-xl transition
                        {{ $item['active']
                            ? 'bg-indigo-50 text-indigo-600'
                            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
                        }}"
                    >
                        <x-dynamic-component
                            :component="$item['icon']"
                            class="h-5 w-5"
                        />
                    </a>

                @endforeach

            </nav>

        </div>
           <a
                href="{{ route('posts.create') }}"
                @click.prevent="$dispatch('open-post-create')"
                title="Novo Post"
                class="group flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-[#7B1FF7] via-[#C31BEB] via-[#FF4FA3] to-[#FFD23F] text-white shadow-sm transition-all duration-300 hover:scale-105"
            >
                <x-lucide-circle-plus class="h-7 w-7 block group-hover:hidden" />
                <x-lucide-badge-plus class="h-7 w-7 hidden group-hover:block" />
            </a>

        {{-- Área Direita --}}
        <div class="flex items-center gap-4">

        

            {{-- Pesquisa (agora à direita do botão) --}}
            <div class="hidden md:block">

                <div class="relative">

                    <x-lucide-search
                        class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                    />

                    <input
                        type="text"
                        placeholder="Pesquisar posts..."
                        class="w-full rounded-full border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>

            </div>

            {{-- Avatar --}}
            <x-dropdown
                align="right"
                position="bottom"
                width="64"
            >
                <x-slot name="trigger">

                    <button
                        class="flex items-center gap-2 rounded-full p-1 transition hover:bg-gray-100"
                    >
                        <x-user-avatar
                            :user="Auth::user()"
                            size="w-10 h-10"
                        />
                    </button>

                </x-slot>

                <x-slot name="content">

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

                    <x-dropdown-link :href="route('profile.edit')">

                        <div class="flex items-center gap-3">
                            <x-lucide-user class="h-4 w-4 text-gray-500" />
                            <span>Perfil</span>
                        </div>

                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
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

    </div>
</header>