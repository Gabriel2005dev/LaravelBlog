<x-app-layout>
    <div class="fixed inset-0 z-[100] flex justify-end">
        <a href="{{ route('feed') }}" class="absolute inset-0 bg-black/50 backdrop-blur-sm" aria-label="Voltar ao feed"></a>

            <aside class="relative flex h-screen w-full max-w-xl flex-col overflow-y-auto bg-white shadow-2xl">
            <header class="sticky top-0 z-20 border-b bg-white/90 backdrop-blur">
                <div class="flex items-center gap-3 px-6 py-4">
                    <a href="{{ route('feed') }}" class="rounded-full p-2 transition hover:bg-gray-100">
                        <x-lucide-arrow-left class="h-5 w-5" />
                    </a>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Novo post</h2>
                        <p class="text-sm text-gray-500">Escreva uma publicação com até 1200 caracteres.</p>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-6">
                <form method="POST" action="{{ route('posts.store') }}">
                    @include('posts._form', ['buttonText' => 'Publicar', 'maxBody' => 1200])
                </form>
            </div>
       </aside>
    </div>
</x-app-layout>