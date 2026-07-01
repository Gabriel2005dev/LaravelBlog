<div>
    <!-- He who is contented is rich. - Laozi -->
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
        mode: 'create',
        open: false,
        selectedPost: null,

        get post() {
            return this.posts.find((post) => post.id === this.selectedPost) || null;
        },

        openCreate() {
            this.mode = 'create';
            this.selectedPost = null;
            this.open = true;
        },

        openEdit(id) {
            this.mode = 'edit';
            this.selectedPost = id;
            this.open = true;
        },

        close() {
            this.open = false;
            this.selectedPost = null;
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
    <div x-show="open" x-transition.opacity @click="close()" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <aside
        x-show="open"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative flex h-screen w-full max-w-xl flex-col overflow-y-auto bg-white shadow-2xl"
    >
        <header class="sticky top-0 z-20 border-b bg-white/90 backdrop-blur">
            <div class="flex items-center gap-3 px-6 py-4">
                <button type="button" @click="close()" class="rounded-full p-2 transition hover:bg-gray-100">
                    <x-lucide-arrow-left class="h-5 w-5" />
                </button>

                <div>
                    <h2 class="text-lg font-bold text-gray-900" x-text="mode === 'create' ? 'Novo post' : 'Editar post'"></h2>
                    <p class="text-sm text-gray-500" x-text="mode === 'create' ? 'Escreva uma publicação com até 1200 caracteres.' : 'Mantenha o conteúdo dentro do limite de 1200 caracteres.'"></p>
                </div>
            </div>
        </header>

        <div class="flex-1 p-6">
            <form x-show="mode === 'create'" method="POST" action="{{ route('posts.store') }}">
                @include('posts._form', ['buttonText' => 'Publicar', 'maxBody' => 1200, 'inline' => true])
            </form>

            <template x-if="mode === 'edit' && post">
                <form method="POST" :action="post.updateUrl">
                    @csrf
                    @method('PUT')

                    <div x-data="{ body: post.body, title: post.title, max: 1200 }">
                        <div>
                            <x-input-label for="drawer-title" value="Título" />
                            <x-text-input id="drawer-title" name="title" type="text" class="mt-1 block w-full rounded-2xl" x-model="title" required autofocus maxlength="255" />
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center justify-between gap-3">
                                <x-input-label for="drawer-body" value="Conteúdo" />
                                <span class="text-xs" :class="body.length >= max ? 'text-red-500' : 'text-gray-400'">
                                    <span x-text="max - body.length"></span> / <span x-text="max"></span>
                                </span>
                            </div>
                            <textarea id="drawer-body" name="body" rows="10" required minlength="10" maxlength="1200" :maxlength="max" x-model="body" @input="if (body.length > max) body = body.slice(0, max)" class="mt-1 block w-full resize-none rounded-2xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :class="body.length >= max ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''" placeholder="Compartilhe uma ideia, atualização ou aprendizado..."></textarea>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>Salvar alterações</x-primary-button>
                            <button type="button" @click="close()" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</button>
                        </div>
                    </div>
                </form>
            </template>
        </div>
    </aside>
</div>
</div>