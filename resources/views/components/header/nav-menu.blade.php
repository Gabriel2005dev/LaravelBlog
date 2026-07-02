<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
@php
    $navItems = collect(config('navigation'))
        ->map(function ($item) {
            return [
                ...$item,
                'href' => route($item['route']),
                'active' => request()->routeIs($item['route']),
            ];
        });
@endphp

<nav class="hidden items-center gap-2 lg:flex">

    @foreach ($navItems as $item)

        <x-header.nav-item
            :href="$item['href']"
            :label="$item['label']"
            :icon="$item['icon']"
            :active="$item['active']"
        />

    @endforeach

    {{-- Novo Post --}}
    <a
        href="{{ route('posts.create') }}"
        @click.prevent="$dispatch('open-post-create')"
        title="Novo Post"
        aria-label="Novo Post"
        class="group flex h-10 w-10 items-center justify-center rounded-tl-2xl rounded-br-2xl text-pink-600 transition-all duration-300 hover:scale-110"
    >
        <x-lucide-circle-plus
            class="block h-6 w-6 transition-all duration-300 group-hover:hidden"
        />

        <x-lucide-badge-plus
            class="hidden h-6 w-6 transition-all duration-300 group-hover:block group-hover:rotate-90"
        />
    </a>

</nav>
</div>