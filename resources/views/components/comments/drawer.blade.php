<div
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

                        <p class="text-sm text-gray-500">
                            Participe da conversa.
                        </p>
                    </div>

                </div>

            </div>

        </header>

        {{-- BODY --}}
        <div class="flex-1 overflow-y-auto">

            {{-- POST --}}
            <section class="border-b p-6 dark:border-zinc-800">

                <div class="flex gap-4">

                    <div class="h-11 w-11 rounded-full bg-gray-200"></div>

                    <div class="flex-1">

                        <div class="flex items-center gap-2">

                            <div class="h-4 w-36 rounded bg-gray-200"></div>

                            <div class="h-3 w-16 rounded bg-gray-100"></div>

                        </div>

                        <div class="mt-4 space-y-2">

                            <div class="h-4 w-full rounded bg-gray-100"></div>

                            <div class="h-4 w-10/12 rounded bg-gray-100"></div>

                            <div class="h-4 w-8/12 rounded bg-gray-100"></div>

                        </div>

                    </div>

                </div>

            </section>

            {{-- COMMENTS --}}
            <section class="p-6">

                <h3 class="mb-6 text-sm font-semibold uppercase tracking-wide text-gray-500">
                    Comentários
                </h3>

                @for ($i = 0; $i < 4; $i++)

                    <div class="mb-8 flex gap-4">

                        <div class="h-10 w-10 rounded-full bg-gray-200"></div>

                        <div class="flex-1">

                            <div class="flex items-center gap-2">

                                <div class="h-4 w-32 rounded bg-gray-200"></div>

                                <div class="h-3 w-14 rounded bg-gray-100"></div>

                            </div>

                            <div class="mt-3 space-y-2">

                                <div class="h-4 w-full rounded bg-gray-100"></div>

                                <div class="h-4 w-9/12 rounded bg-gray-100"></div>

                            </div>

                        </div>

                    </div>

                @endfor

            </section>

        </div>

        {{-- FOOTER --}}
        <footer class="border-t bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">

            <div class="flex items-end gap-3">

                <div class="h-10 w-10 rounded-full bg-gray-200"></div>

                <textarea
                    rows="2"
                    placeholder="Escreva um comentário..."
                    class="w-full resize-none rounded-2xl border-gray-300 text-sm focus:border-violet-500 focus:ring-violet-500"
                ></textarea>

                <button
                    class="rounded-full bg-gradient-to-br from-[#7B1FF7] via-[#C31BEB] via-[#FF4FA3] to-[#FFD23F] px-5 py-2 font-semibold text-white transition hover:scale-105"
                >
                    Enviar
                </button>

            </div>

        </footer>

    </aside>

</div>