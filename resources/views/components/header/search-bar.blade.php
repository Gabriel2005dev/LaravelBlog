<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
     <div
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="
        open = false;
        $refs.search.value = '';
        $refs.search.blur();
    "
    class="relative flex h-10 items-center"
>

    {{-- Campo --}}
    <div
        class="relative flex h-10 items-center overflow-hidden rounded-full border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-out"
        :class="open ? 'w-64' : 'w-10 border-transparent shadow-none'"
    >

        {{-- Botão Pesquisa --}}
        <button
            type="button"
            title="Pesquisar"
            @click="
                if (!open) {
                    open = true;
                    $nextTick(() => $refs.search.focus());
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
            type="text"
            placeholder="Pesquisar..."
            class="h-full w-full border-0 bg-transparent pl-10 pr-10 text-sm outline-none ring-0 placeholder:text-gray-400 focus:border-0 focus:ring-0"
        >

        {{-- Fechar --}}
        <button
            x-show="open"
            x-transition.opacity.duration.150ms
            @click="
                open = false;
                $refs.search.value = '';
                $refs.search.blur();
            "
            class="absolute right-3 text-gray-400 transition hover:text-gray-600"
        >
            <x-lucide-x class="h-4 w-4"/>
        </button>

    </div>

</div>
</div>