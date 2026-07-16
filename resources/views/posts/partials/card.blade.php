@props(['post', 'expanded' => false])

@php
    $comments = $post->comments ?? collect();
    $shareUrl = route('feed');

    $body = trim($post->body);

   $previewLength = 800;

if (mb_strlen($body) > $previewLength) {

    $preview = mb_substr($body, 0, $previewLength);

    // Remove a última palavra incompleta
    $preview = preg_replace('/\s+\S*$/u', '', $preview);

    // Remove espaços no final
    $preview = rtrim($preview);

    // Adiciona reticências
    $preview .= '...';

    $hasMore = true;

} else {

    $preview = $body;

    $hasMore = false;
}
@endphp

<article
    class="bg-white border border-gray-100 rounded overflow-hidden
           transition-all duration-300 shadow"
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

            const data = {
                title: @js($post->title),
                text: @js(\Illuminate\Support\Str::limit($post->body,120)),
                url: @js($shareUrl)
            };

            if (navigator.share) {
                await navigator.share(data);
            } else {
                await navigator.clipboard.writeText(data.url);
            }

            this.shared = true;

            setTimeout(() => {
                this.shared = false;
            }, 2000);
        }
    }"


>

    {{-- HEADER --}}
    <div class="flex items-start justify-between p-4">

        <div class="flex items-center gap-4 min-w-0">

            <x-user-avatar :user="$post->user" size="w-10 h-10" />

            <div class="justify-center min-w-0 flex flex-col">

                <a
                class="text-md font-bold text-gray-900 hover:text-indigo-600 transition truncate">
                    @if(auth()->id() === $post->user_id)
                        <span class="bg-pink-600 bg-clip-text text-transparent font-bold">
                            Você
                        </span>
                    @else
                        {{ $post->user->name }}
                    @endif
                </a>

                <span class="text-xs text-gray-400 mt-0.5">
                    {{ $post->created_at->diffForHumans() }}
                </span>

            </div>

        </div>

        @can('update', $post)
        <div class="flex items-center">

           <a
                @click.prevent="$dispatch('open-post-edit', { id: {{ $post->id }} })"
                class="p-2 rounded-full text-gray-500 hover:text-pink-600 transition"
                title="Editar publicação">

                <x-lucide-pencil class="w-5 h-5" />
            </a>

            <form method="POST"
                action="{{ route('posts.destroy', $post) }}"
                onsubmit="return confirm('Excluir esta publicação?')">
                @csrf
                @method('DELETE')

                <button
                    class="p-2 rounded-full text-red-600 hover:text-red-600 transition">

                    <x-lucide-trash-2 class="w-5 h-5" />
                </button>
            </form>

        </div>
       
        @endcan

    </div>

    {{-- BODY --}}
    <div class="px-4">

        <a class="block text-xl font-bold tracking-tight text-gray-900 transition">
            {{ $post->title }}
        </a>

    <div class="mt-3">

    <p
        class="text-sm text-gray-600 leading-2 break-words whitespace-pre-line transition-all duration-300"
        x-text="expandedText ? @js($body) : @js($preview)">
    </p>

    @if($hasMore)

        <button
            type="button"
            @click="expandedText = !expandedText"
            class="mt-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">

            <span x-show="!expandedText">
                Saiba mais
            </span>

            <span x-show="expandedText">
                Mostrar menos
            </span>

        </button>

    @endif

</div>

    </div>
    {{-- ACTIONS --}}
<div class="p-4">

    <div class="flex items-center justify-between">

        {{-- Grupo da esquerda --}}
        <div class="flex items-center gap-2">

            {{-- LIKE --}}
            <div>
                <button
                    type="button"
                    @click="toggleLike"
                    :disabled="liking"
                    class="group flex items-center gap-2 px-3 py-1.5 rounded-full text-sm transition"
                    :class="liked ? 'text-pink-600' : 'text-gray-600 hover:text-pink-600'">

                    <x-lucide-heart class="h-5 w-5 fill-current" x-show="liked" />

                    <span x-show="!liked" class="contents">
                        <x-lucide-heart
                            class="h-5 w-5 block group-hover:hidden transition-all duration-200" />

                        <x-lucide-heart-handshake
                            class="h-5 w-5 hidden group-hover:block transition-all duration-200" />
                    </span>

                    <span x-text="likesCount"></span>

                </button>

            </div>

            {{-- COMMENTS --}}
            <button
                type="button"
                @click="
                    selectedPost = {{ $post->id }};
                    commentsDrawer = true;
                "
                class="group flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:text-pink-600 transition">

                <x-lucide-message-circle
                    class="h-5 w-5 block group-hover:hidden transition-all duration-200" />

                <x-lucide-message-circle-more
                    class="h-5 w-5 hidden group-hover:block transition-all duration-200" />

                <span x-text="commentsCount"></span>

            </button>

            {{-- SHARE --}}
            <button
                type="button"
                @click="share()"
                class="group flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:text-pink-600 transition">

                <x-lucide-share-2
                    class="h-5 w-5 block group-hover:hidden transition-all duration-200" />

                <x-lucide-waypoints
                    class="h-5 w-5 hidden group-hover:block transition-all duration-200" />

                <span x-text="shared ? 'Copiado!' : 'Compartilhar'"></span>

            </button>

        </div>

        {{-- Grupo da direita --}}
        {{-- Grupo da direita --}}
<div>
    <button
        type="button"
        @click="toggleSave"
        :disabled="saving"
        class="group relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-tl-2xl rounded-br-2xl transition-colors duration-200
        "
        :class="saved ? 'border-0' : 'border border-gray-200 bg-white'"
    >

        {{-- Fundo --}}
        <span
            class="absolute inset-0 rounded-tl-2xl rounded-br-2xl bg-pink-600 transition-opacity duration-200
            "
            :class="saved ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'"
        ></span>

        <span x-show="saved" class="relative z-10 flex h-5 w-5 items-center justify-center">
                <x-lucide-bookmark-check
                    class="h-5 w-5 text-white stroke-[2.2]"
                />
            </span>

            <span x-show="!saved" class="relative z-10 flex h-5 w-5 items-center justify-center">

                {{-- Ícone normal --}}
                <x-lucide-bookmark
                    class="absolute h-5 w-5 text-gray-600 transition-all duration-200 group-hover:scale-90 group-hover:opacity-0"
                />

                {{-- Hover --}}
                <x-lucide-bookmark-check
                    class="absolute h-5 w-5 scale-90 text-white opacity-0 transition-all duration-200 group-hover:scale-100 group-hover:opacity-100"
                />

            </span>
    </button>

</div>
     
   
    </div>

</div>
  

      
</article>