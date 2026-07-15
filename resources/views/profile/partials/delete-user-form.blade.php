<section class="flex flex-col h-full">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Excluir Conta
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Ao excluir sua conta, todos os seus dados e recursos serão removidos permanentemente. Antes de prosseguir, faça o download de qualquer informação que deseje manter.
        </p>
    </header>

    <div class="flex justify-end mt-auto pt-6">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center gap-2"
        >
            Excluir Conta
            <x-lucide-trash-2 class="w-4 h-4" />
        </x-danger-button>
    </div>

    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >
        <form method="POST" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900">
                Tem certeza de que deseja excluir sua conta?
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                Esta ação é permanente e não poderá ser desfeita. Todos os seus dados serão removidos definitivamente.
                Para confirmar a exclusão da sua conta, digite sua senha abaixo.
            </p>

            <div class="mt-6">
                <x-input-label
                    for="password"
                    value="Senha"
                    class="sr-only"
                />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Digite sua senha"
                />

                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                    class="mt-2"
                />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="inline-flex items-center gap-2">
                    Excluir Conta
                </x-danger-button>
                <x-lucide-trash-2 class="w-4 h-4" />
            </div>
        </form>
    </x-modal>
</section>