<div>
    @props(['posts' => collect()])

    @php
        $drawerSource = $posts instanceof \Illuminate\Contracts\Pagination\Paginator
            ? $posts->getCollection()
            : collect($posts);

        $drawerPosts = $drawerSource->map(fn ($post) => [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'updateUrl' => route('posts.update', $post),
        ])->values();
    @endphp

    <div
        x-data="{
            posts: @js($drawerPosts),

            open: false,
            mode: 'create',

            post: {
                id: null,
                title: '',
                body: '',
                updateUrl: null,
            },

            max: 1200,

            get remaining() {
                return this.max - (this.post.body?.length || 0)
            },

            get isLimit() {
                return (this.post.body?.length || 0) >= this.max
            },

            openCreate() {
                this.mode = 'create'
                this.post = {
                    id: null,
                    title: '',
                    body: '',
                    updateUrl: null
                }
                this.open = true
            },

            openEdit(id) {
                const found = this.posts.find(p => p.id === id)

                this.mode = 'edit'
                this.post = {
                    id: found.id,
                    title: found.title,
                    body: found.body,
                    updateUrl: found.updateUrl
                }

                this.open = true
            },

            close() {
                this.open = false
            }
        }"

        x-on:open-post-create.window="openCreate()"
        x-on:open-post-edit.window="openEdit($event.detail.id)"
        x-on:close-post-drawer.window="close()"
        x-show="open"
        x-cloak
        @keydown.escape.window="close()"
        class="fixed inset-0 z-[100] flex justify-end"
    >

        {{-- OVERLAY --}}
        <div
            x-show="open"
            x-transition.opacity
            @click="close()"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        ></div>

        {{-- DRAWER --}}
        <aside
            x-show="open"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="relative flex h-screen w-full max-w-xl flex-col overflow-y-auto
                   bg-white shadow-2xl dark:bg-zinc-900"
        >

            {{-- HEADER --}}
            <header class="sticky top-0 z-20 bg-white/90 backdrop-blur dark:bg-zinc-900/90">
                <div class="flex items-center gap-3 px-6 py-4">

                    <button
                        type="button"
                        @click="close()"
                        class="rounded-full p-2 transition hover:bg-pink-100 hover:text-pink-600 "
                    >
                        <x-lucide-arrow-left class="h-5 w-5" />
                    </button>

                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white"
                            x-text="mode === 'create' ? 'Novo post' : 'Editar post'"></h2>

                        <p class="text-sm text-gray-500 dark:text-zinc-400"
                           x-text="mode === 'create'
                                ? 'Escreva uma publicação com até 1200 caracteres.'
                                : 'Edite sua publicação dentro do limite de 1200 caracteres.'">
                        </p>
                    </div>

                </div>
            </header>

            {{-- BODY --}}
            <div class="flex-1 p-6">

                {{-- FORM ÚNICO (CREATE + EDIT) --}}
                <form
                    method="POST"
                    :action="mode === 'create'
                        ? '{{ route('posts.store') }}'
                        : post.updateUrl"
                >
                    @csrf

                    <template x-if="mode === 'edit'">
                        @method('PUT')
                    </template>

                    {{-- TITLE --}}
                    <div>
                        <x-input-label for="title" value="Título" />

                        <input
                            id="title"
                            name="title"
                            type="text"
                            required
                            maxlength="255"
                            x-model="post.title"
                            class="mt-1 block w-full rounded-full border-gray-300 shadow-sm
                                   focus:border-pink-600 focus:ring-pink-600
                                   dark:bg-zinc-800 dark:border-zinc-700 dark:text-white"
                        />
                    </div>

                    {{-- BODY --}}
                    <div class="mt-4">
                        <x-input-label for="body" value="Conteúdo" />

                        <div class="relative mt-1">

                            <textarea
                                id="body"
                                name="body"
                                rows="10"
                                required
                                minlength="10"
                                maxlength="1200"
                                x-model="post.body"
                                @input="if (post.body.length > max) post.body = post.body.slice(0, max)"
                                placeholder="Compartilhe uma ideia, atualização ou aprendizado..."
                                class="w-full resize-none rounded-3xl border p-4 pb-10 pr-12 text-sm shadow-sm
                                       transition focus:outline-none focus:ring-pink-600 focus:border-pink-600
                                       dark:bg-zinc-800 dark:border-zinc-700 dark:text-white"
                                :class="isLimit
                                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                                    : 'border-gray-300 dark:border-zinc-700'"
                            ></textarea>

                            {{-- COUNTER --}}
                            <div
                                class="absolute bottom-3 left-4 text-xs"
                                :class="isLimit ? 'text-red-500' : 'text-gray-400'"
                            >
                                <span x-text="remaining"></span> / <span x-text="max"></span>
                            </div>

                            {{-- SUBMIT --}}
                            <button
                                type="submit"
                                class="absolute bottom-4 right-2 flex h-8 w-8 items-center justify-center
                                       rounded-full bg-pink-600 text-white transition hover:scale-105"
                                title="Salvar"
                            >
                                <x-lucide-send class="h-4 w-4" />
                            </button>

                        </div>
                    </div>

                </form>

            </div>

        </aside>
    </div>
</div>