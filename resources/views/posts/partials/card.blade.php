@props(['post', 'expanded' => false])

@php
    $comments = $post->comments ?? collect();
    $shareUrl = route('posts.show', $post);
@endphp

<article
    class="bg-white p-5 shadow-sm ring-1 ring-gray-100 sm:rounded-2xl"
    x-data="{
        editing: {{ $errors->any() && old('post_id') == $post->id ? 'true' : 'false' }},
        commentsOpen: {{ $expanded ? 'true' : 'false' }},
        visibleComments: 5,
        shared: false,
        async share() {
            const data = { title: @js($post->title), text: @js(\Illuminate\Support\Str::limit($post->body, 120)), url: @js($shareUrl) };
            if (navigator.share) { await navigator.share(data); this.shared = true; return; }
            await navigator.clipboard.writeText(data.url); this.shared = true;
            setTimeout(() => this.shared = false, 2200);
        },
        closeComments() { this.commentsOpen = false; this.visibleComments = 5; }
    }"
>
    <div class="flex items-start gap-4">
        <x-user-avatar :user="$post->user" size="w-11 h-11" />

        <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                        <a href="{{ route('posts.show', $post) }}" class="font-semibold text-gray-950 hover:text-indigo-700">{{ $post->user->name }}</a>
                        <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('posts.show', $post) }}" class="mt-1 block text-lg font-bold text-gray-900 hover:text-indigo-700">{{ $post->title }}</a>
                </div>

                @can('update', $post)
                    <div class="flex shrink-0 items-center gap-2">
                        <button type="button" @click="editing = ! editing" class="rounded-full p-2 text-gray-500 hover:bg-gray-100 hover:text-indigo-700" title="Editar inline">
                            <x-lucide-pencil class="h-5 w-5" />
                        </button>
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Excluir esta publicação?')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-full p-2 text-gray-500 hover:bg-red-50 hover:text-red-700" title="Excluir">
                                <x-lucide-trash-2 class="h-5 w-5" />
                            </button>
                        </form>
                    </div>
                @endcan
            </div>

            <p class="mt-3 whitespace-pre-line text-gray-700">{{ $expanded ? $post->body : \Illuminate\Support\Str::limit($post->body, 260) }}</p>

            @can('update', $post)
                <form x-show="editing" x-cloak method="POST" action="{{ route('posts.update', $post) }}" class="mt-4 rounded-2xl border border-indigo-100 bg-indigo-50/40 p-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    @include('posts._form', ['post' => $post, 'buttonText' => 'Salvar alterações', 'inline' => true])
                </form>
            @endcan

            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t pt-4 text-sm text-gray-500">
                <div class="flex items-center gap-2 sm:gap-5">
                    <form method="POST" action="{{ route('posts.like.toggle', $post) }}">
                        @csrf
                        <button class="flex items-center gap-2 rounded-full px-3 py-2 transition {{ $post->liked_by_current_user ? 'bg-red-50 text-red-600' : 'hover:bg-red-50 hover:text-red-600' }}">
                            <x-lucide-heart class="h-5 w-5 {{ $post->liked_by_current_user ? 'fill-current' : '' }}" />
                            <span>{{ $post->likes_count }} curtidas</span>
                        </button>
                    </form>

                    <button type="button" @click="commentsOpen = true" class="flex items-center gap-2 rounded-full px-3 py-2 hover:bg-blue-50 hover:text-blue-600">
                        <x-lucide-message-circle class="h-5 w-5" />
                        <span>{{ $post->comments_count }} comentários</span>
                    </button>

                    <button type="button" @click="share()" class="flex items-center gap-2 rounded-full px-3 py-2 hover:bg-emerald-50 hover:text-emerald-600">
                        <x-lucide-share-2 class="h-5 w-5" />
                        <span x-text="shared ? 'Link copiado' : 'Compartilhar'"></span>
                    </button>
                </div>

                <form method="POST" action="{{ route('posts.save.toggle', $post) }}">
                    @csrf
                    <button class="flex items-center gap-2 rounded-full px-3 py-2 transition {{ $post->saved_by_current_user ? 'bg-amber-50 text-amber-600' : 'hover:bg-amber-50 hover:text-amber-600' }}">
                        <x-lucide-bookmark class="h-5 w-5 {{ $post->saved_by_current_user ? 'fill-current' : '' }}" />
                        <span>{{ $post->saved_by_current_user ? 'Salvo' : 'Salvar' }}</span>
                    </button>
                </form>
            </div>

            <section x-show="commentsOpen" x-cloak class="mt-5 rounded-2xl bg-gray-50 p-4">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Comentários</h3>
                    <button type="button" @click="closeComments()" class="text-sm font-medium text-gray-500 hover:text-gray-900">Fechar comentários</button>
                </div>

                <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="flex gap-3">
                    @csrf
                    <x-user-avatar :user="auth()->user()" size="w-9 h-9" />
                    <div class="flex-1">
                        <textarea name="body" rows="2" class="block w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escreva um comentário..." required></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('body')" />
                        <x-primary-button class="mt-2">Comentar</x-primary-button>
                    </div>
                </form>

                <div class="mt-5 space-y-4">
                    @forelse ($comments as $comment)
                        <div class="flex gap-3 border-t border-gray-200 pt-4" x-show="{{ $loop->iteration }} <= visibleComments">
                            <x-user-avatar :user="$comment->user" size="w-9 h-9" />
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-gray-900">{{ $comment->user->name }}</p>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-700">{{ $comment->body }}</p>
                            </div>
                            @can('delete', $comment)
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Excluir este comentário?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-medium text-red-600 hover:text-red-800">Excluir</button>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Ainda não há comentários.</p>
                    @endforelse
                </div>

                @if ($comments->count() > 5)
                    <button type="button" x-show="visibleComments < {{ $comments->count() }}" @click="visibleComments += 5" class="mt-4 text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        Ver mais comentários
                    </button>
                @endif
            </section>
        </div>
    </div>
</article>