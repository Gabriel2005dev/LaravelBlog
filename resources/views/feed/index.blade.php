<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center justify-between">

        <!-- Lado Esquerdo -->
        <div class="flex items-center gap-16">

            <!-- Bloco de ações -->
            <div class="flex items-center gap-4">

                <button class="text-gray-500 hover:text-red-500 transition">
                    <x-lucide-heart class="w-6 h-6" />
                </button>

                <button class="text-gray-500 hover:text-yellow-500 transition">
                    <x-lucide-bookmark class="w-6 h-6" />
                </button>

                <button class="text-gray-500 hover:text-green-500 transition">
                    <x-lucide-share-2 class="w-6 h-6" />
                </button>

            </div>

        </div>

        <!-- Lado Direito -->
        <div class="flex items-center gap-4">

            <button class="relative text-gray-500 hover:text-indigo-600 transition">
                <x-lucide-bell class="w-5 h-5" />

                <!-- Badge de exemplo -->
                <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <a href="{{ route('posts.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">

                <x-lucide-square-pen class="w-4 h-4" />
                Novo Post
            </a>

        </div>

    </div>
</x-slot>

    <div class="py-12">
            <div class="max-w-4xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

                        @forelse ($posts as $post)
                <article class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <a href="{{ route('posts.show', $post) }}" class="text-2xl font-bold text-gray-900 hover:text-indigo-700">{{ $post->title }}</a>
                            <p class="mt-1 text-sm text-gray-500">Por {{ $post->user->name }} em {{ $post->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @can('update', $post)
                            <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Editar</a>
                        @endcan
                    </div>
                    <p class="mt-4 text-gray-700">{{ \Illuminate\Support\Str::limit($post->body, 240) }}</p>
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                        <span>{{ $post->comments_count ?? $post->comments->count() }} comentário(s)</span>
                        <a href="{{ route('posts.show', $post) }}" class="font-medium text-indigo-600 hover:text-indigo-800">Ler mais</a>
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