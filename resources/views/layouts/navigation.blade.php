<header class="fixed inset-x-0 top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur supports-[padding:max(0px)]:pt-[env(safe-area-inset-top)]">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-2 px-3 sm:px-4 lg:px-0">
        <x-header.logo />

        <div class="hidden lg:block">
            <x-header.nav-menu />
        </div>

        <div class="flex min-w-0 items-center justify-end gap-2 sm:gap-3">
            <x-header.search-bar />
            <x-header.user-menu />
        </div>
    </div>
</header>

@auth
    @php
        $mobileNavItems = collect(config('navigation'))->map(fn ($item) => [
            ...$item,
            'href' => route($item['route']),
            'active' => request()->routeIs($item['route']),
        ]);
    @endphp

    <nav
        class="fixed inset-x-0 bottom-0 z-50 border-t border-gray-200 bg-white/95 px-2 py-1.5 shadow-[0_-8px_24px_rgba(15,23,42,0.08)] backdrop-blur lg:hidden"
        aria-label="Navegação mobile"
    >
        <div class="mx-auto grid max-w-md grid-cols-4">

            {{-- Itens do menu --}}
            @foreach ($mobileNavItems as $item)
                <a
                    href="{{ $item['href'] }}"
                    title="{{ $item['label'] }}"
                    aria-label="{{ $item['label'] }}"
                    class="group flex flex-col items-center justify-center gap-0.5 py-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-pink-600"
                >

                    {{-- Bola --}}
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-white transition-all duration-300
                        {{ $item['active']
                            ? '-translate-y-2 scale-110 shadow-lg'
                            : 'shadow-none group-hover:-translate-y-2 group-hover:scale-110 group-hover:shadow-lg' }}"
                    >
                        <x-dynamic-component
                            :component="$item['icon']"
                            class="h-5 w-5 transition-colors duration-300
                            {{ $item['active']
                                ? 'text-pink-700'
                                : 'text-gray-600 group-hover:text-pink-600' }}"
                        />
                    </div>
                    {{-- Indicador --}}
                    <span
                        class="h-1.5 w-1.5 rounded-full bg-pink-600 transition-all duration-300
                        {{ $item['active']
                            ? 'opacity-100 scale-100'
                            : 'opacity-0 scale-0 group-hover:opacity-100 group-hover:scale-100' }}"
                    ></span>


                    

                </a>
            @endforeach

            {{-- Novo Post --}}
            <button
                type="button"
                @click="$dispatch('open-post-create')"
                title="Novo Post"
                aria-label="Novo Post"
                class="group flex flex-col items-center justify-center gap-0.5 py-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-pink-600"
            >

                {{-- Bola --}}
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-white transition-all duration-300 shadow-none group-hover:-translate-y-2 group-hover:scale-110 group-hover:shadow-lg"
                >
                    <x-lucide-circle-plus
                        class="h-5 w-5 text-pink-700"
                    />
                </div>
                {{-- Indicador --}}
                <span
                    class="h-1.5 w-1.5 rounded-full bg-pink-600 opacity-0 scale-0 transition-all duration-300 group-hover:opacity-100 group-hover:scale-100"
                ></span>

             

            </button>

        </div>
    </nav>
@endauth