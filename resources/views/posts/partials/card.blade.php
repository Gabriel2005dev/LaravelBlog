@props(['post', 'expanded' => false])

@php
    $comments = $post->comments ?? collect();
    $shareUrl = route('posts.show', $post);
@endphp

<article
    class="bg-white border border-gray-100 rounded-tl-5xl rounded-br-5xl overflow-hidden
           transition-all duration-300 shadow"
x-data="{
    editing: {{ $errors->any() && old('post_id') == $post->id ? 'true' : 'false' }},
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
    <div class="px-4">

        <a href="{{ route('posts.show', $post) }}"
           class="block text-xl font-bold tracking-tight text-gray-900 hover:text-indigo-600 transition">
            {{ $post->title }}
        </a>

        <p class="text-sm text-gray-600 whitespace-pre-line">
            {{ $expanded ? $post->body : \Illuminate\Support\Str::limit($post->body, 1500) }}
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
    <div class="p-4">

        <div class="flex items-center justify-between flex-wrap gap-3">

        <div class="flex items-center justify-between flex-wrap gap-4">

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
            {{-- Clicado: coração preenchido --}}
            <x-lucide-heart
                class="h-4 w-4 fill-current" />
        @else
            {{-- Normal --}}
            <x-lucide-heart
                class="h-4 w-4 block group-hover:hidden transition-all duration-200" />

            {{-- Hover --}}
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

    {{-- Normal --}}
    <x-lucide-message-circle
        class="h-4 w-4 block group-hover:hidden transition-all duration-200" />

    {{-- Hover --}}
    <x-lucide-message-circle-more
        class="h-4 w-4 hidden group-hover:block transition-all duration-200" />

    {{ $post->comments_count }}
</button>

{{-- SHARE --}}
<button
    type="button"
    @click="share()"
    class="group flex items-center gap-2 px-3 py-1.5 rounded-full text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition">

    {{-- Normal --}}
    <x-lucide-share-2
        class="h-4 w-4 block group-hover:hidden transition-all duration-200" />

    {{-- Hover --}}
    <x-lucide-waypoints
        class="h-4 w-4 hidden group-hover:block transition-all duration-200" />

    <span x-text="shared ? 'Copiado!' : 'Compartilhar'"></span>
</button>

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
</article>