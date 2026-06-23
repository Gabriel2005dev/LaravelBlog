<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $post->title }}</h2>
            <a href="{{ route('feed') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Voltar ao feed</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <article class="bg-white p-6 shadow-sm sm:rounded-lg">
                <p class="text-sm text-gray-500">Por {{ $post->user->name }} em {{ $post->created_at->format('d/m/Y H:i') }}</p>
                <div class="prose mt-4 max-w-none whitespace-pre-line text-gray-800">{{ $post->body }}</div>

                @can('update', $post)
                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('posts.edit', $post) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Editar</a>
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Excluir esta publicação?')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Excluir</button>
                        </form>
                    </div>
                @endcan
            </article>

            <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900">Comentários</h3>
                <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="mt-4">
                    @csrf
                    <textarea name="body" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escreva um comentário..." required>{{ old('body') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('body')" />
                    <x-primary-button class="mt-3">Comentar</x-primary-button>
                </form>

                <div class="mt-6 space-y-4">
                    @forelse ($post->comments as $comment)
                        <div class="border-t pt-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $comment->user->name }}</p>
                                    <p class="mt-1 text-gray-700">{{ $comment->body }}</p>
                                </div>
                                @can('delete', $comment)
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Excluir este comentário?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-sm text-red-600 hover:text-red-800">Excluir</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Ainda não há comentários.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>