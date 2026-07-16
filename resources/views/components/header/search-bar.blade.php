<div
    x-data="{ open: @js(request()->filled('search')) }"
    @click.outside="open = false"
    @keydown.escape.window="
        open = false;
        $refs.search.value = '';
        $refs.search.blur();
    "
    class="relative flex h-10 items-center"
>
    <form
        method="GET"
        action="{{ route('feed') }}"
        class="relative flex h-10 items-center overflow-hidden rounded-full bg-white shadow transition-all duration-300 ease-out"
        :class="open ? 'w-64' : 'w-10 border-transparent shadow'"
    >
        {{-- Botão Pesquisa --}}
        <button
            type="button"
            title="Pesquisar"
            @click="
                if (!open) {
                    open = true;
                    $nextTick(() => $refs.search.focus());
                } else if ($refs.search.value.trim() !== '') {
                    $el.closest('form').submit();
                }
            "
            class="absolute z-10 flex h-10 w-10 items-center justify-center text-gray-500 transition-all duration-300 hover:text-pink-600"
            :style="
                open
                    ? 'transform:translateX(0)'
                    : 'transform:translateX(calc(100% - 2.5rem))'
            "
        >
            <x-lucide-search class="h-5 w-5"/>
        </button>

        {{-- Input --}}
        <input
            x-ref="search"
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Pesquisar Post..."
            class="h-full w-full border-0 bg-transparent pl-10 pr-10 text-sm outline-none ring-0 placeholder:text-gray-400 focus:border-0 focus:ring-0"
        >

        {{-- Fechar --}}
        <a
            x-show="open"
            x-transition.opacity.duration.150ms
            href="{{ route('feed') }}"
            @click="
                if ($refs.search.value === '') {
                    open = false;
                    $refs.search.blur();
                    $event.preventDefault();
                }
            "
            class="absolute right-3 text-gray-400 transition hover:text-gray-600"
            title="Limpar pesquisa"
        >
            <x-lucide-circle-x class="h-5 w-5"/>
        </a>
    </form>
</div>