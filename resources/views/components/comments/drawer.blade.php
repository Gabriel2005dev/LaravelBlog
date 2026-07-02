@props(['posts' => collect()])

@php
    $drawerSource = $posts instanceof \Illuminate\Contracts\Pagination\Paginator
        ? $posts->getCollection()
        : collect($posts);

    $drawerPosts = $drawerSource->map(function ($post) {
        $avatarUrl = fn ($user) => $user->avatar
            ? asset('storage/' . $user->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name);

        return [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'authorName' => auth()->id() === $post->user_id ? 'Você' : $post->user->name,
            'authorAvatar' => $avatarUrl($post->user),
            'createdAt' => $post->created_at->diffForHumans(),
            'commentsCount' => $post->comments_count ?? $post->comments->count(),
            'commentStoreUrl' => route('posts.comments.store', $post),
            'comments' => $post->comments->map(fn ($comment) => [
                'id' => $comment->id,
                'body' => $comment->body,
                'authorName' => auth()->id() === $comment->user_id ? 'Você' : $comment->user->name,
                'authorAvatar' => $avatarUrl($comment->user),
                'createdAt' => $comment->created_at->diffForHumans(),
                'canDelete' => auth()->check() && auth()->user()->can('delete', $comment),
                'deleteUrl' => route('comments.destroy', $comment),
            ])->values(),
        ];
    })->values();
@endphp

<div
    x-data="{
        posts: @js($drawerPosts),
        
        get post() {
            return this.posts.find((post) => post.id === this.selectedPost) || null;
        }
    }"
    x-show="commentsDrawer"
    x-cloak
    @keydown.escape.window="commentsDrawer = false"
    class="fixed inset-0 z-[100] flex justify-end"
>

    {{-- Overlay --}}
    <div
        x-show="commentsDrawer"
        x-transition.opacity
        @click="commentsDrawer = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    {{-- Drawer --}}
    <aside
        x-show="commentsDrawer"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative flex h-screen w-full max-w-xl flex-col overflow-x-hidden bg-white shadow-2xl dark:bg-zinc-900"
    >

        {{-- HEADER --}}
        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/90">

            <div class="flex items-center justify-between px-6 py-4">

                <div class="flex items-center gap-3">

                    <button
                        @click="commentsDrawer = false"
                        class="rounded-full p-2 transition hover:bg-gray-100 dark:hover:bg-zinc-800"
                    >
                        <x-lucide-arrow-left class="h-5 w-5"/>
                    </button>

                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            Comentários
                        </h2>

                        <p class="text-sm text-gray-500" x-text="post ? `${post.commentsCount} comentário(s) nesta publicação.` : 'Selecione uma publicação.'">
                        </p>
                    </div>

                </div>

            </div>

        </header>

        {{-- BODY --}}
        <template x-if="post">
    <div class="flex-1 overflow-y-auto">
      

            {{-- POST --}}
            <section
            
            
            class="p-6">

                <div class="flex gap-4">

                    <img :src="post.authorAvatar" :alt="`Avatar de ${post.authorName}`" class="h-11 w-11 rounded-full border object-cover">

                    <div class="flex-1 min-w-0">

                        <div class="flex flex-wrap items-center gap-2">

                            <h3 class="font-semibold text-gray-900 dark:text-white" x-text="post.authorName"></h3>

                            <span class="text-sm text-gray-400" x-text="post.createdAt"></span>

                        </div>

                        <h4 class="mt-3 text-lg font-bold text-gray-900 dark:text-white" x-text="post.title"></h4>

                        <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-zinc-300 whitespace-pre-wrap break-words"
                        x-text="post.body">
                        </p>

                    </div>

                </div>

            </section>

            {{-- COMMENTS --}}
            <section class="p-6">
                <template x-if="post.comments.length === 0">
                    <p class="rounded bg-gray-50 p-5 text-center text-sm text-gray-500 dark:bg-zinc-800 dark:text-zinc-300">
                        Ainda não há comentários. Seja a primeira pessoa a comentar.
                    </p>
                </template>

                <template x-for="comment in post.comments" :key="comment.id">
                    <div class="mb-8 flex gap-4 min-w-0">

                        <img :src="comment.authorAvatar" :alt="`Avatar de ${comment.authorName}`" class="h-10 w-10 rounded-full border object-cover">

                        <div class="flex-1">

                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-white" x-text="comment.authorName"></span>
                                    <span class="text-sm text-gray-400" x-text="comment.createdAt"></span>
                                </div>

                                <form x-show="comment.canDelete" method="POST" :action="comment.deleteUrl" onsubmit="return confirm('Excluir este comentário?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-gray-400 transition hover:text-red-600" title="Excluir comentário">
                                        <x-lucide-trash-2 class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>

                              <p
                                class="mt-2 text-sm leading-5 text-gray-600 dark:text-zinc-300 whitespace-pre-line break-words [overflow-wrap:anywhere]"
                                x-text="comment.body"
                            ></p>
                        </div>

                    </div>
                </template>

            </section>
        </div>
        </template>

      <template x-if="!post">
        <div class="flex-1 place-content-center p-6 text-center text-sm text-gray-500">
            Não foi possível carregar os comentários desta publicação.
        </div>
    </template>

        {{-- FOOTER --}}
        <template x-if="post">
       <footer
    class="border-t bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900"
    x-data="{
        sending: false,
        body: @js(old('body', '')),
        max: 400,

        get remaining() {
            return this.max - this.body.length
        },

        get isLimit() {
            return this.body.length >= this.max
        }
    }"
>

    @auth
        <form
            method="POST"
            :action="post.commentStoreUrl"
            class="flex items-start gap-3"
            @submit="if (sending) { $event.preventDefault(); return; } sending = true"
        >
            @csrf

            {{-- AVATAR (ALINHADO NO TOPO) --}}
            <img
                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                alt="Avatar de {{ Auth::user()->name }}"
                class="mt-1 h-10 w-10 rounded-full border object-cover"
            >

            {{-- INPUT --}}
            <div class="relative flex-1">
                <textarea
                    name="body"
                    rows="2"
                    required
                    minlength="3"
                    maxlength="400"
                    :maxlength="max"
                    x-model="body"
                    @input="if (body.length > max) body = body.slice(0, max)"
                    placeholder="Escreva um comentário..."
                    class="w-full resize-none rounded-xl border pb-8 pr-14 pt-3 text-sm transition
                        focus:outline-none focus:ring-pink-600 focus:border-pink-600"
                    :class="isLimit
                        ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                        : 'border-gray-300'
                    "
                ></textarea>

                {{-- CONTADOR --}}
                <div
                    class="absolute bottom-3 left-3 text-xs"
                    :class="isLimit ? 'text-red-500' : 'text-gray-400'"
                >
                    <span x-text="remaining"></span> / 400
                </div>

                {{-- BOTÃO DENTRO DO INPUT (CIRCULAR) --}}
                <button
                    type="submit"
                    :disabled="sending || body.trim().length === 0"
                    class="absolute bottom-3 right-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-pink-600 text-white transition hover:scale-105 disabled:cursor-not-allowed disabled:opacity-50"
                    :title="sending ? 'Enviando comentário' : 'Enviar comentário'"
                >
                    <x-lucide-send class="h-4 w-4" x-show="!sending" />
                    <x-lucide-loader-circle class="h-4 w-4 animate-spin" x-show="sending" />
                </button>
            </div>
        </form>
    @else
        <a href="{{ route('login') }}" class="block rounded-xl bg-gray-50 p-4 text-center text-sm font-semibold text-indigo-600 hover:bg-indigo-50">
            Entre para participar da conversa.
        </a>
    @endauth

</footer>
</template>
    </aside>

</div>