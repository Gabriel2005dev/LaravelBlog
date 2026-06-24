@props(['post', 'expanded' => false])

@php
    $comments = $post->comments ?? collect();
    $shareUrl = route('posts.show', $post);
@endphp

<article
    class="bg-white border border-gray-100 shadow-sm rounded-2xl hover:shadow-md transition overflow-hidden "
    x-data="{
        editing: {{ $errors->any() && old('post_id') == $post->id ? 'true' : 'false' }},
        commentsOpen: {{ $expanded ? 'true' : 'false' }},
        visibleComments: 5,
        shared: false,
        async share() {
            const data = {
                title: @js($post->title),
                text: @js(\Illuminate\Support\Str::limit($post->body, 120)),
                url: @js($shareUrl)
            };

            if (navigator.share) {
                await navigator.share(data);
            } else {
                await navigator.clipboard.writeText(data.url);
            }

            this.shared = true;
            setTimeout(() => this.shared = false, 2000);
        },
        closeComments() {
            this.commentsOpen = false;
            this.visibleComments = 5;
        }
    }"
>

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-3 min-w-0">
            <x-user-avatar :user="$post->user" size="w-10 h-10" />

            <div class="min-w-0 leading-tight">
                <a href="{{ route('posts.show', $post) }}"
                   class="font-semibold text-gray-900 hover:text-indigo-600 truncate block">
                    {{ $post->user->name }}
                </a>

                <span class="text-xs text-gray-400">
                    {{ $post->created_at->diffForHumans() }}
                </span>
            </div>
        </div>

        @can('update', $post)
            <div class="flex items-center gap-1">

                <button type="button"
                    @click="editing = ! editing"
                    class="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition">
                    <x-lucide-pencil class="h-4 w-4" />
                </button>

                <form method="POST"
                      action="{{ route('posts.destroy', $post) }}"
                      onsubmit="return confirm('Excluir esta publicação?')">
                    @csrf
                    @method('DELETE')

                    <button class="p-2 rounded-full hover:bg-red-50 text-gray-500 hover:text-red-600 transition">
                        <x-lucide-trash-2 class="h-4 w-4" />
                    </button>
                </form>

            </div>
        @endcan
    </div>

    {{-- BODY --}}
    <div class="px-5 pt-4 pb-3">

        <a href="{{ route('posts.show', $post) }}"
           class="block text-lg font-bold text-gray-900 hover:text-indigo-600 transition">
            {{ $post->title }}
        </a>

        <p class="mt-2 text-sm text-gray-600 leading-relaxed">
            {{ $expanded ? $post->body : \Illuminate\Support\Str::limit($post->body, 220) }}
        </p>

        @can('update', $post)
            <form x-show="editing"
                  x-cloak
                  method="POST"
                  action="{{ route('posts.update', $post) }}"
                  class="mt-4 p-4 rounded-xl bg-gray-50 border border-gray-100">
                @csrf
                @method('PATCH')
                <input type="hidden" name="post_id" value="{{ $post->id }}">

                @include('posts._form', [
                    'post' => $post,
                    'buttonText' => 'Salvar alterações',
                    'inline' => true
                ])
            </form>
        @endcan
    </div>

    {{-- ACTIONS --}}
    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">

        <div class="flex items-center gap-2">

            <form method="POST" action="{{ route('posts.like.toggle', $post) }}">
                @csrf
                <button class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm transition
                    {{ $post->liked_by_current_user
                        ? 'bg-red-50 text-red-600'
                        : 'hover:bg-red-50 text-gray-600 hover:text-red-600' }}">
                    <x-lucide-heart class="h-4 w-4 {{ $post->liked_by_current_user ? 'fill-current' : '' }}" />
                    {{ $post->likes_count }}
                </button>
            </form>

            <button type="button"
                    @click="commentsOpen = true"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                <x-lucide-message-circle class="h-4 w-4" />
                {{ $post->comments_count }}
            </button>

            <button type="button"
                    @click="share()"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition">
                <x-lucide-share-2 class="h-4 w-4" />
                <span x-text="shared ? 'Copiado!' : 'Compartilhar'"></span>
            </button>

        </div>

        <form method="POST" action="{{ route('posts.save.toggle', $post) }}">
            @csrf
            <button class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm transition
                {{ $post->saved_by_current_user
                    ? 'bg-amber-50 text-amber-600'
                    : 'hover:bg-amber-50 text-gray-600 hover:text-amber-600' }}">
                <x-lucide-bookmark class="h-4 w-4 {{ $post->saved_by_current_user ? 'fill-current' : '' }}" />
                Salvar
            </button>
        </form>

    </div>

    {{-- COMMENTS --}}
    <section x-show="commentsOpen"
             x-cloak
             class="bg-gray-50 px-5 py-4">

        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900">Comentários</h3>

            <button @click="closeComments()"
                    class="text-sm text-gray-500 hover:text-gray-900">
                Fechar
            </button>
        </div>

        <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="flex gap-3">
            @csrf

            <x-user-avatar :user="auth()->user()" size="w-9 h-9" />

            <div class="flex-1">
                <textarea name="body"
                          rows="2"
                          class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Escreva um comentário..."
                          required></textarea>

                <x-primary-button class="mt-2">Comentar</x-primary-button>
            </div>
        </form>

        <div class="mt-4 space-y-4">

            @forelse ($comments as $comment)
                <div class="flex gap-3 pt-3 border-t border-gray-100"
                     x-show="{{ $loop->iteration }} <= visibleComments">

                    <x-user-avatar :user="$comment->user" size="w-9 h-9" />

                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $comment->user->name }}
                            </p>

                            <span class="text-xs text-gray-400">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mt-1">
                            {{ $comment->body }}
                        </p>
                    </div>

                    @can('delete', $comment)
                        <form method="POST"
                              action="{{ route('comments.destroy', $comment) }}">
                            @csrf
                            @method('DELETE')

                            <button class="text-xs text-red-500 hover:text-red-700">
                                Excluir
                            </button>
                        </form>
                    @endcan
                </div>
            @empty
                <p class="text-sm text-gray-500">Sem comentários ainda.</p>
            @endforelse

        </div>

        @if ($comments->count() > 5)
            <button type="button"
                    x-show="visibleComments < {{ $comments->count() }}"
                    @click="visibleComments += 5"
                    class="mt-4 text-sm text-indigo-600 font-medium hover:text-indigo-800">
                Ver mais
            </button>
        @endif

    </section>

</article>