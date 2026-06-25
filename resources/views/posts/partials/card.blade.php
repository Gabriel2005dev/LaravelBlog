@props(['post', 'expanded' => false])

@php
    $comments = $post->comments ?? collect();
    $shareUrl = route('posts.show', $post);
@endphp

<article
    class="bg-white border border-gray-100 rounded-xl rounded-tl-4xl overflow-hidden
           shadow-[0_8px_30px_rgb(0,0,0,0.04)]
           hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)]
           transition-all duration-300"
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
    <div class="flex items-start justify-between p-2">

        <div class="flex items-center gap-4 min-w-0">

            <x-user-avatar :user="$post->user" size="w-14 h-14" />

            <div class="flex flex-col justify-center min-w-0">

                <a href="{{ route('posts.show', $post) }}"
                   class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition truncate">
                    {{ $post->user->name }}
                </a>

                <span class="text-sm text-gray-400 mt-0.5">
                    {{ $post->created_at->diffForHumans() }}
                </span>

            </div>

        </div>

        @can('update', $post)
        <div class="flex items-center">

            <button
                type="button"
                @click="editing = ! editing"
                class="p-2 rounded-full text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 transition">

                <x-lucide-pencil class="w-4 h-4" />
            </button>

            <form method="POST"
                action="{{ route('posts.destroy', $post) }}"
                onsubmit="return confirm('Excluir esta publicação?')">
                @csrf
                @method('DELETE')

                <button
                    class="p-2 rounded-full text-gray-500 hover:bg-red-50 hover:text-red-600 transition">

                    <x-lucide-trash-2 class="w-4 h-4" />
                </button>
            </form>

        </div>
       
        @endcan

    </div>

    {{-- BODY --}}
    <div class="px-6 py-6">

        <a href="{{ route('posts.show', $post) }}"
           class="block text-xl font-bold tracking-tight text-gray-900 hover:text-indigo-600 transition">
            {{ $post->title }}
        </a>

        <p class="mt-4 text-[15px] leading-8 text-gray-600">
            {{ $expanded ? $post->body : \Illuminate\Support\Str::limit($post->body, 220) }}
        </p>

        @can('update', $post)
            <form
                x-show="editing"
                x-cloak
                method="POST"
                action="{{ route('posts.update', $post) }}"
                class="mt-6 p-5 rounded-2xl border border-gray-100 bg-gray-50">
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
    <div class="border-t border-gray-100 p-2">

        <div class="flex items-center justify-between flex-wrap gap-3">

        <div class="flex items-center justify-between flex-wrap gap-4">

    <div class="flex items-center gap-2">

        {{-- LIKE --}}
        <form method="POST" action="{{ route('posts.like.toggle', $post) }}">
            @csrf

            <button
                class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm transition
                {{ $post->liked_by_current_user
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }}">

                <x-lucide-heart
                    class="h-4 w-4 {{ $post->liked_by_current_user ? 'fill-current' : '' }}" />

                {{ $post->likes_count }}
            </button>
        </form>

        {{-- COMMENTS --}}
        <button
            type="button"
            @click="commentsOpen = true"
            class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">

            <x-lucide-message-circle class="h-4 w-4" />

            {{ $post->comments_count }}
        </button>

        {{-- SHARE --}}
        <button
            type="button"
            @click="share()"
            class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition">

            <x-lucide-share-2 class="h-4 w-4" />

            <span x-text="shared ? 'Copiado!' : 'Compartilhar'"></span>
        </button>

    </div>
</div>

            {{-- SAVE --}}
            <form method="POST" action="{{ route('posts.save.toggle', $post) }}">
                @csrf

                <button
                    class="flex items-center gap-2
                           px-3 py-1.5 rounded-full border
                           transition text-sm

                           {{ $post->saved_by_current_user
                               ? ' border-amber-100 text-amber-600'
                               : 'bg-white border-gray-200 text-gray-600' }}">

                    <x-lucide-bookmark
                        class="w-4 h-4 {{ $post->saved_by_current_user ? 'fill-current' : '' }}" />

                    Salvar
                </button>

            </form>

        </div>

    </div>

    {{-- COMMENTS --}}
    <section
        x-show="commentsOpen"
        x-cloak
        class="bg-gray-50 border-t border-gray-100 px-6 py-5">

        <div class="flex items-center justify-between mb-5">

            <h3 class="font-semibold text-gray-900">
                Comentários
            </h3>

            <button
                @click="closeComments()"
                class="text-sm text-gray-500 hover:text-gray-900">
                Fechar
            </button>

        </div>

        <form
            method="POST"
            action="{{ route('posts.comments.store', $post) }}"
            class="flex gap-3">

            @csrf

            <x-user-avatar :user="auth()->user()" size="w-10 h-10" />

            <div class="flex-1">

                <textarea
                    name="body"
                    rows="2"
                    required
                    placeholder="Escreva um comentário..."
                    class="w-full rounded-2xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>

                <x-primary-button class="mt-3">
                    Comentar
                </x-primary-button>

            </div>

        </form>

        <div class="mt-5 space-y-4">

            @forelse ($comments as $comment)

                <div
                    class="flex gap-3 pt-4 border-t border-gray-100"
                    x-show="{{ $loop->iteration }} <= visibleComments">

                    <x-user-avatar :user="$comment->user" size="w-10 h-10" />

                    <div class="flex-1">

                        <div class="flex items-center gap-2">

                            <span class="font-medium text-gray-900">
                                {{ $comment->user->name }}
                            </span>

                            <span class="text-xs text-gray-400">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>

                        </div>

                        <p class="text-sm text-gray-600 mt-1">
                            {{ $comment->body }}
                        </p>

                    </div>

                    @can('delete', $comment)
                        <form
                            method="POST"
                            action="{{ route('comments.destroy', $comment) }}">

                            @csrf
                            @method('DELETE')

                            <button
                                class="text-xs text-red-500 hover:text-red-700">
                                Excluir
                            </button>

                        </form>
                    @endcan

                </div>

            @empty

                <p class="text-sm text-gray-500">
                    Sem comentários ainda.
                </p>

            @endforelse

        </div>

        @if ($comments->count() > 5)

            <button
                type="button"
                x-show="visibleComments < {{ $comments->count() }}"
                @click="visibleComments += 5"
                class="mt-5 text-sm font-medium text-indigo-600 hover:text-indigo-800">

                Ver mais comentários

            </button>

        @endif

    </section>

</article>