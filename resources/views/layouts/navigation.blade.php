<header class="fixed inset-x-0 top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">

    <div class="mx-auto grid h-16 max-w-6xl grid-cols-[1fr_auto_1fr] items-center px-2 lg:px-0">

        {{-- ============================================================
            Logo
        ============================================================= --}}
        <div class="justify-self-start">
            <x-header.logo />
        </div>

        {{-- ============================================================
            Navegação
        ============================================================= --}}
        <div class="justify-self-center">
            <x-header.nav-menu />
        </div>

        {{-- ============================================================
            Ações do usuário
        ============================================================= --}}
        <div class="flex items-center justify-self-end gap-3">

            <x-header.search-bar />

            <x-header.notification-button />

            <x-header.user-menu />

            

        </div>

    </div>

</header>