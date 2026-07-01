@props(['post', 'expanded' => false])

@php
    $comments = $post->comments ?? collect();
    $shareUrl = route('posts.show', $post);

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
    class="bg-white border border-gray-100 rounded-tl-5xl rounded-br-5xl overflow-hidden
           transition-all duration-300 shadow"
    x-data="{
        editing: {{ $errors->any() && old('post_id') == $post->id ? 'true' : 'false' }},
        shared: false,
        expandedText: false,

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

                <a href="{{ route('posts.show', $post) }}"
                class="text-md font-bold text-gray-900 hover:text-indigo-600 transition truncate">
                    @if(auth()->id() === $post->user_id)
                        <span class="bg-gradient-to-br from-[#7B1FF7] via-[#C31BEB] via-[#FF4FA3] to-[#FFD23F] bg-clip-text text-transparent font-bold">
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
                href="{{ route('posts.edit', $post) }}"
                @click.prevent="$dispatch('open-post-edit', { id: {{ $post->id }} })"
                class="p-2 rounded-full text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 transition"
                title="Editar publicação">

                <x-lucide-pencil class="w-4 h-4" />
            </a>

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
    <div class="px-4">

        <a href="{{ route('posts.show', $post) }}"
           class="block text-xl font-bold tracking-tight text-gray-900 hover:text-indigo-600 transition">
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
            <form method="POST" action="{{ route('posts.like.toggle', $post) }}">
                @csrf

                <button
                    class="group flex items-center gap-2 px-3 py-1.5 rounded-full text-sm transition
                    {{ $post->liked_by_current_user
                        ? 'bg-red-50 text-red-600'
                        : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }}">

                    @if($post->liked_by_current_user)

                        <x-lucide-heart class="h-4 w-4 fill-current" />

                    @else

                        <x-lucide-heart
                            class="h-4 w-4 block group-hover:hidden transition-all duration-200" />

                        <x-lucide-heart-handshake
                            class="h-4 w-4 hidden group-hover:block transition-all duration-200" />

                    @endif

                    <span>{{ $post->likes_count }}</span>

                </button>

            </form>

            {{-- COMMENTS --}}
            <button
                type="button"
                @click="
                    selectedPost = {{ $post->id }};
                    commentsDrawer = true;
                "
                class="group flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">

                <x-lucide-message-circle
                    class="h-4 w-4 block group-hover:hidden transition-all duration-200" />

                <x-lucide-message-circle-more
                    class="h-4 w-4 hidden group-hover:block transition-all duration-200" />

                <span>{{ $post->comments_count }}</span>

            </button>

            {{-- SHARE --}}
            <button
                type="button"
                @click="share()"
                class="group flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition">

                <x-lucide-share-2
                    class="h-4 w-4 block group-hover:hidden transition-all duration-200" />

                <x-lucide-waypoints
                    class="h-4 w-4 hidden group-hover:block transition-all duration-200" />

                <span x-text="shared ? 'Copiado!' : 'Compartilhar'"></span>

            </button>

        </div>

        {{-- Grupo da direita --}}
        <form method="POST" action="{{ route('posts.save.toggle', $post) }}">
    @csrf

    <button
        type="submit"
        class="group relative flex items-center justify-center
               w-10 h-10 rounded-full overflow-hidden
               transition-colors duration-200

               {{ $post->saved_by_current_user
                    ? 'border-0'
                    : 'bg-white border border-gray-200'
               }}">

        {{-- Fundo gradiente --}}
        <span
            class="absolute inset-0 rounded-full
                   bg-gradient-to-br
                   from-[#7B1FF7]
                   via-[#C31BEB]
                   via-[#FF4FA3]
                   to-[#FFD23F]
                   transition-opacity duration-200

                   {{ $post->saved_by_current_user
                        ? 'opacity-100'
                        : 'opacity-0 group-hover:opacity-100'
                   }}">
        </span>

        @if ($post->saved_by_current_user)

            <x-lucide-bookmark-check
                class="relative z-10 w-5 h-5 text-white stroke-white" />

        @else

            <div class="relative z-10 w-5 h-5">

                {{-- Ícone normal --}}
                <x-lucide-bookmark
                    class="absolute inset-0 w-5 h-5
                           text-gray-600
                           transition-opacity duration-200
                           opacity-100 group-hover:opacity-0" />

                {{-- Ícone no hover --}}
                <x-lucide-bookmark-check
                    class="absolute inset-0 w-5 h-5
                           text-white stroke-white
                           transition-opacity duration-200
                           opacity-0 group-hover:opacity-100" />

            </div>

        @endif

    </button>
</form>
   
    </div>

</div>
  

      
</article>