<x-app-layout
>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">

            <div class="flex items-center">
                <a href="{{ route('posts.liked.index') }}"
                    class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-semibold {{ request()->routeIs('posts.liked.index') ? 'bg-red-50 text-red-600' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-lucide-heart class="h-5 w-5" />
                    Curtidos
                </a>

                <a href="{{ route('posts.saved.index') }}"
                    class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-semibold {{ request()->routeIs('posts.saved.index') ? 'bg-amber-50 text-amber-600' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-lucide-bookmark class="h-5 w-5" />
                    Salvos
                </a>

                <a href="{{ route('feed') }}#feed"
                    class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100 hover:text-gray-900"
                    title="As notificações ficam concentradas no feed de atividades por enquanto.">
                    <x-lucide-bell class="h-5 w-5" />
                    Atividades
                </a>
            </div>

            <div class="flex-1 lg:max-w-md">
                <form method="GET" action="{{ route('feed') }}" class="relative">
                    <button
                        type="submit"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-indigo-500"
                        title="Pesquisar posts">
                        <x-lucide-search class="h-5 w-5" />
                    </button>

                    <input
                        type="search"
                        name="search"
                        value="{{ $search ?? request('search') }}"
                        placeholder="Pesquisar posts..."
                        class="w-full rounded-full border-gray-300 py-2 pl-10 pr-10 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                    @if (($search ?? request('search')) !== null && ($search ?? request('search')) !== '')
                        <a
                            href="{{ route('feed') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-gray-600"
                            title="Limpar pesquisa">
                            <x-lucide-x class="h-4 w-4" />
                        </a>
                    @endif
                </form>
            </div>

            <a
                @click.prevent="$dispatch('open-post-create')"
                class="group inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-br from-[#7B1FF7] via-[#C31BEB] via-[#FF4FA3] to-[#FFD23F] px-3 py-1.5 text-sm font-semibold text-white shadow-sm">

                {{-- Normal --}}
                <x-lucide-circle-plus
                    class="h-5 w-5 block group-hover:hidden transition-all duration-200" />

                {{-- Hover --}}
                <x-lucide-badge-plus
                    class="h-5 w-5 hidden group-hover:block transition-all duration-200" />

                Novo
            </a>

        </div>
    </x-slot>

    <div id="feed">
        <div class="mx-auto items-center max-w-6xl space-y-3">

            @if (session('status'))
                <div class="rounded-full bg-green-50 p-4 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if (($search ?? '') !== '')
                <div class="rounded-full bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                    Resultado da pesquisa por <strong>"{{ $search }}"</strong>.
                    <a href="{{ route('feed') }}" class="font-semibold underline">Limpar</a>
                </div>
            @endif

            @forelse ($posts as $post)
                @include('posts.partials.card', ['post' => $post])
            @empty
                <div class="rounded-full bg-white p-8 text-center shadow-sm ring-1 ring-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Nenhum post encontrado
                    </h3>

                    <p class="mt-2 text-gray-600">
                        @if (($search ?? '') !== '')
                            Tente pesquisar por outro termo ou limpe a pesquisa para voltar ao feed completo.
                        @else
                            Compartilhe uma publicação ou explore o feed do LaravelBlog.
                        @endif
                    </p>
                </div>
            @endforelse

            {{ $posts->links() }}

        </div>
    </div>

    {{-- Drawers --}}
    <x-posts.drawer :posts="$posts" />
    <x-comments.drawer :posts="$posts" />
    
</x-app-layout>