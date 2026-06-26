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
        selectedPost: null,
        get post() {
            return this.posts.find((post) => post.id === selectedPost) || null;
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
        class="relative flex h-screen w-full max-w-xl flex-col overflow-hidden bg-white shadow-2xl dark:bg-zinc-900"
    >

        {{-- HEADER --}}
        <header class="sticky top-0 z-20 border-b bg-white/90 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/90">

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
        <div class="flex-1 overflow-y-auto" x-show="post">

            {{-- POST --}}
            <section
            
            
            class="border-b p-6 dark:border-zinc-800">

                <div class="flex gap-4">

                    <img :src="post.authorAvatar" :alt="`Avatar de ${post.authorName}`" class="h-11 w-11 rounded-full border object-cover">

                    <div class="flex-1">

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

                <h3 class="mb-6 text-sm font-semibold uppercase tracking-wide text-gray-500">
                    Comentários
                </h3>

                <template x-if="post.comments.length === 0">
                    <p class="rounded-2xl bg-gray-50 p-5 text-center text-sm text-gray-500 dark:bg-zinc-800 dark:text-zinc-300">
                        Ainda não há comentários. Seja a primeira pessoa a comentar.
                    </p>
                </template>

                <template x-for="comment in post.comments" :key="comment.id">
                    <div class="mb-8 flex gap-4">

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
                            <div x-data="{ expanded: false }" class="mt-2">

                                <p class="text-sm text-gray-600 dark:text-zinc-300 whitespace-pre-wrap break-words">
                                    <span x-text="expanded 
                                        ? comment.body 
                                        : (comment.body.length > 200 
                                            ? comment.body.slice(0, 200) + '...' 
                                            : comment.body)">
                                    </span>
                                </p>

                                <template x-if="comment.body.length > 200">
                                    <button
                                        @click="expanded = !expanded"
                                        class="mt-1 text-xs font-semibold text-violet-600 hover:underline"
                                        x-text="expanded ? 'Mostrar menos' : 'Saiba mais'"
                                    ></button>
                                </template>

                            </div>


                        </div>

                    </div>
                </template>

            </section>

        </div>

        <div class="flex-1 place-content-center p-6 text-center text-sm text-gray-500" x-show="! post">
            Não foi possível carregar os comentários desta publicação.
        </div>

        {{-- FOOTER --}}
        <footer
            class="border-t bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900"
            x-show="post"
            x-data="{ sending: false }"
        >

            @auth
            <form
                method="POST"
                :action="post.commentStoreUrl"
                class="flex items-end gap-3"
                @submit="if (sending) $event.preventDefault(); sending = true"
            >
               
                    @csrf

                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="Avatar de {{ Auth::user()->name }}" class="h-10 w-10 rounded-full border object-cover">

                    <textarea
                        name="body"
                        rows="2"
                        required
                        minlength="3"
                        maxlength="400"
                        placeholder="Escreva um comentário..."
                        class="w-full resize-none rounded-2xl border-gray-300 text-sm focus:border-violet-500 focus:ring-violet-500"
                    ></textarea>

                    <p class="text-xs text-gray-400 mt-1">
                        Máximo de 400 caracteres
                    </p>

                    <button
                        :disabled="sending"
                        class="rounded-full bg-gradient-to-br from-[#7B1FF7] via-[#C31BEB] via-[#FF4FA3] to-[#FFD23F] px-5 py-2 font-semibold text-white transition hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span x-show="!sending">Enviar</span>
                        <span x-show="sending">Enviando...</span>
                    </button>

                </form>
            @else
                <a href="{{ route('login') }}" class="block rounded-2xl bg-gray-50 p-4 text-center text-sm font-semibold text-indigo-600 hover:bg-indigo-50">
                    Entre para participar da conversa.
                </a>
            @endauth

        </footer>

    </aside>

</div>