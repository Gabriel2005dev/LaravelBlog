<x-app-layout>
    <x-slot name="header">
         <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Feed</h2>
            <a href="{{ route('posts.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Novo post</a>
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