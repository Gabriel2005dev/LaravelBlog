<x-app-layout>
   <x-slot name="header">
    <div class="flex items-center justify-between">

        <!-- Lado Esquerdo -->
        <div class="flex items-center">

            <div class="flex items-center gap-4">

                <button class="text-gray-500 hover:text-gray-900 transition">
                    <x-lucide-heart class="w-5 h-5" />
                </button>

                <button class="text-gray-500 hover:text-gray-900 transition">
                    <x-lucide-bookmark class="w-5 h-5" />
                </button>

                <button class="text-gray-500 hover:text-gray-900 transition">
                    <x-lucide-share-2 class="w-5 h-5" />
                </button>

            </div>

        </div>

        <!-- Centro -->
        <div class="flex-1 max-w-md mx-8">
            <div class="relative">
                <x-lucide-search
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />

                <input
                    type="text"
                    placeholder="Pesquisar posts..."
                    class="w-full rounded-full border-gray-300 pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <!-- Lado Direito -->
        <div class="flex items-center gap-6">

            <button class="relative text-gray-500 hover:text-gray-900 transition">
                <x-lucide-bell class="w-5 h-5" />

                <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <a href="{{ route('posts.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">

                <x-lucide-badge-plus class="w-5 h-5" />
                Novo Post
            </a>

        </div>

    </div>
</x-slot>

    <div>
            <div class="max-w-7xl mx-auto space-y-2">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

                        @forelse ($posts as $post)
                <article class="bg-white p-6 shadow-sm sm:rounded-2xl">
                    <div class="flex items-start justify-between gap-4">
                       <div class="mt-2 flex items-center gap-2">
    <span class="text-md text-gray-900">
        {{ $post->user->name }}
    </span>

    <span class="text-xs text-gray-500">
        {{ $post->created_at->diffForHumans() }}
    </span>
</div>
                        <div class="flex gap-3">
                        @can('update', $post)
                            <a href="{{ route('posts.edit', $post) }}"
                            class="text-gray-500 hover:text-gray-900">
                                <x-lucide-pencil class="w-5 h-5" />
                            </a>
                             <form method="POST"
                                action="{{ route('posts.destroy', $post) }}"
                                onsubmit="return confirm('Excluir esta publicação?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="rounded-md text-gray-500"
                                    title="Excluir">
                                    <x-lucide-trash-2 class="w-5 h-5" />
                                </button>
                            </form>

                        @endcan
                        </div>
                    </div>
                    <p class="mt-4 text-gray-700">{{ \Illuminate\Support\Str::limit($post->body, 240) }}</p>

                    <div class="mt-6 border-t pt-4">
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-6 text-gray-500">

            <!-- Curtir -->
            <button class="flex items-center gap-2 hover:text-red-500 transition">
                <x-lucide-heart class="w-5 h-5" />
                <span>Curtir</span>
            </button>

            <!-- Comentar -->
            <a href="{{ route('posts.show', $post) }}"
               class="flex items-center gap-2 hover:text-blue-500 transition">
                <x-lucide-message-circle class="w-5 h-5" />
                <span>{{ $post->comments_count ?? $post->comments->count() }}</span>
            </a>

    

        </div>

        <!-- Salvar -->
        <button class="flex items-center gap-2 text-gray-500 hover:text-yellow-500 transition">
            <x-lucide-bookmark class="w-5 h-5" />
            <span>Salvar</span>
        </button>

    </div>
</div>
                    
                </article>
            @empty
                <div class="bg-white p-8 text-center shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900">Nenhum post publicado ainda</h3>
                    <p class="mt-2 text-gray-600">Seja a primeira pessoa a compartilhar uma publicação no LaravelBlog.</p>
                </div>
                        @endforelse

                    {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>