@props(['post', 'expanded' => false])

@php
    $shareUrl = route('feed');
    $body = trim($post->body);
    $previewLength = 800;
    $hasMore = mb_strlen($body) > $previewLength;
    $preview = $hasMore ? rtrim(preg_replace('/\s+\S*$/u', '', mb_substr($body, 0, $previewLength))).'...' : $body;
@endphp

<article
    class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 sm:rounded-xl"
    @comment-created.window="if ($event.detail.postId === {{ $post->id }}) commentsCount = $event.detail.commentsCount"
    x-data="{
        editing: {{ $errors->any() && old('post_id') == $post->id ? 'true' : 'false' }},
        shared: false,
        expandedText: false,
        liking: false,
        saving: false,
        liked: @js((bool) $post->liked_by_current_user),
        saved: @js((bool) $post->saved_by_current_user),
        likesCount: @js($post->likes_count),
        commentsCount: @js($post->comments_count),

        async toggleLike() {
            if (this.liking) return;
            this.liking = true;

            try {
                const response = await window.axios.post(@js(route('posts.like.toggle', $post)));
                this.liked = response.data.liked;
                this.likesCount = response.data.likes_count;
            } finally {
                this.liking = false;
            }
        },

        async toggleSave() {
            if (this.saving) return;
            this.saving = true;

            try {
                const response = await window.axios.post(@js(route('posts.save.toggle', $post)));
                this.saved = response.data.saved;
            } finally {
                this.saving = false;
            }
        },

        async share() {

            const data = { title: @js($post->title), text: @js(\Illuminate\Support\Str::limit($post->body, 120)), url: @js($shareUrl) };
            if (navigator.share) await navigator.share(data); else await navigator.clipboard.writeText(data.url);
            
            this.shared = true;

            setTimeout(() => this.shared = false, 2000);
        }
    }"
>

    <header class="flex items-start justify-between gap-3 p-3 sm:p-4">
        <div class="flex min-w-0 items-center gap-3 sm:gap-4">

            <x-user-avatar :user="$post->user" size="w-10 h-10" />

            <div class="min-w-0">
                <p class="truncate text-base font-bold text-gray-900">
                    @if (auth()->id() === $post->user_id)
                        <span class="bg-pink-600 bg-clip-text text-transparent">Você</span>
                    @else
                        {{ $post->user->name }}
                    @endif
                 </p>

                <time class="mt-0.5 block text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</time>

            </div>
        </div>

        @can('update', $post)
            <div class="flex shrink-0 items-center gap-1">
                <button type="button" @click="$dispatch('open-post-edit', { id: {{ $post->id }} })" class="flex min-h-11 min-w-11 items-center justify-center rounded-full text-gray-500 transition hover:bg-pink-50 hover:text-pink-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-pink-600" aria-label="Editar publicação">
                    <x-lucide-pencil class="h-5 w-5" />
                </button>
            <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Excluir esta publicação?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex min-h-11 min-w-11 items-center justify-center rounded-full text-red-600 transition hover:bg-red-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600" aria-label="Excluir publicação">
                        <x-lucide-trash-2 class="h-5 w-5" />
                    </button>
                </form>
            </div>
        @endcan

    </header>

    <div class="px-3 sm:px-4">
        <h2 class="break-words text-lg font-bold tracking-tight text-gray-900 sm:text-xl [overflow-wrap:anywhere]">{{ $post->title }}</h2>
        <div class="mt-3">
            <p class="whitespace-pre-line break-words text-sm leading-6 text-gray-700 [overflow-wrap:anywhere]" x-text="expandedText ? @js($body) : @js($preview)"></p>
            @if ($hasMore)
                <button type="button" @click="expandedText = ! expandedText" class="mt-2 min-h-11 text-sm font-semibold text-indigo-600 transition hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600" aria-label="Alternar texto completo do post">
                    <span x-show="!expandedText">Saiba mais</span>
                    <span x-show="expandedText">Mostrar menos</span>
                </button>
            @endif
        </div>
    </div>
   <footer class="p-3 sm:p-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                <button type="button" @click="toggleLike" :disabled="liking" class="group flex min-h-11 items-center gap-2 rounded-full px-3 text-sm transition disabled:cursor-not-allowed disabled:opacity-60" :class="liked ? 'text-pink-600' : 'text-gray-600 hover:text-pink-600'" aria-label="Curtir publicação">

                    <x-lucide-heart class="h-5 w-5 fill-current" x-show="liked" />

                    <span x-show="!liked" class="contents"><x-lucide-heart class="block h-5 w-5 group-hover:hidden" /><x-lucide-heart-handshake class="hidden h-5 w-5 group-hover:block" /></span>

                    <span x-text="likesCount"></span>

                </button>

                <button type="button" @click="selectedPost = {{ $post->id }}; commentsDrawer = true" class="group flex min-h-11 items-center gap-2 rounded-full px-3 text-sm text-gray-600 transition hover:text-pink-600" aria-label="Abrir comentários">
                    <x-lucide-message-circle class="block h-5 w-5 group-hover:hidden" /><x-lucide-message-circle-more class="hidden h-5 w-5 group-hover:block" />
                    <span x-text="commentsCount"></span>
                </button>
                <button type="button" @click="share()" class="group flex min-h-11 items-center gap-2 rounded-full px-3 text-sm text-gray-600 transition hover:text-pink-600" aria-label="Compartilhar publicação">
                    <x-lucide-share-2 class="block h-5 w-5 group-hover:hidden" /><x-lucide-waypoints class="hidden h-5 w-5 group-hover:block" />
                    <span x-text="shared ? 'Copiado!' : 'Compartilhar'"></span>
                </button>
            </div>

             <button type="button" @click="toggleSave" :disabled="saving" class="group relative flex min-h-11 min-w-11 items-center justify-center overflow-hidden rounded-tl-2xl rounded-br-2xl transition-colors duration-200 disabled:cursor-not-allowed disabled:opacity-60" :class="saved ? 'border-0' : 'border border-gray-200 bg-white'" aria-label="Salvar publicação">
                <span class="absolute inset-0 rounded-tl-2xl rounded-br-2xl bg-pink-600 transition-opacity duration-200" :class="saved ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'"></span>
                <x-lucide-bookmark-check x-show="saved" class="relative z-10 h-5 w-5 text-white" />
                <x-lucide-bookmark x-show="!saved" class="relative z-10 h-5 w-5 text-gray-600 transition group-hover:text-white" />
            </button>
        </div>
   </footer>
</article>

      