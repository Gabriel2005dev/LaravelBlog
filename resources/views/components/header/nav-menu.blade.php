@php
    $navItems = collect(config('navigation'))->map(fn ($item) => [
        ...$item,
        'href' => route($item['route']),
        'active' => request()->routeIs($item['route']),
    ]);
@endphp

<nav class="hidden items-center gap-2 lg:flex" aria-label="Navegação principal">

    @foreach ($navItems as $item)

    <x-header.nav-item 
    :href="$item['href']" 
    :label="$item['label']" 
    :icon="$item['icon']" 
    :active="$item['active']" />

    @endforeach

    <button
        type="button"
        @click="$dispatch('open-post-create')"
        title="Novo Post"
        aria-label="Novo Post"
        class="group flex min-h-9 min-w-9 items-center justify-center rounded-tl-2xl rounded-br-2xl text-pink-600 transition-all duration-300 hover:scale-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-pink-600 focus-visible:ring-offset-2"
    >
        <x-lucide-circle-plus class="block h-6 w-6 transition-all duration-300 group-hover:hidden" />
        <x-lucide-badge-plus class="hidden h-6 w-6 transition-all duration-300 group-hover:block group-hover:rotate-90" />
    </button>

</nav>
