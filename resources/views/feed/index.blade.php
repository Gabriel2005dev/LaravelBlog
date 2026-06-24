<x-app-layout>

        <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between ">
            <div class="flex items-center gap-3">
                <a href="{{ route('posts.liked.index') }}" class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-semibold {{ request()->routeIs('posts.liked.index') ? 'bg-red-50 text-red-600' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-lucide-heart class="h-5 w-5" /> Curtidos
                </a>
                <a href="{{ route('posts.saved.index') }}" class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-semibold {{ request()->routeIs('posts.saved.index') ? 'bg-amber-50 text-amber-600' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-lucide-bookmark class="h-5 w-5" /> Salvos
                </a>
                <a href="{{ route('feed') }}#feed" class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100 hover:text-gray-900" title="As notificações ficam concentradas no feed de atividades por enquanto.">
                    <x-lucide-bell class="h-5 w-5" /> Atividades
                </a>
            </div>

                <div class="flex-1 lg:max-w-md">
                <div class="relative">
                    <x-lucide-search class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                    <input type="text" placeholder="Pesquisar posts..." class="w-full rounded-full border-gray-300 py-3 pl-10 pr-4 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <a href="{{ route('posts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                <x-lucide-badge-plus class="h-5 w-5" /> Novo Post
            </a>
        </div>
    </x-slot>

        <div id="feed" class="py-8">
        <div class="mx-auto max-w-6xl space-y-5">
            @if (session('status'))
                <div class="rounded-full bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif  
                </article>
            @forelse ($posts as $post)
                @include('posts.partials.card', ['post' => $post])
            @empty
                <div class="rounded-full bg-white p-8 text-center shadow-sm ring-1 ring-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Nenhum post encontrado</h3>
                    <p class="mt-2 text-gray-600">Compartilhe uma publicação ou explore o feed do LaravelBlog.</p>
                </div>
            @endforelse
            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>