<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                {{-- Informações do Perfil --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    @include('profile.partials.profile-summary')
                </div>

                {{-- Dados da Conta --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Alterar Senha --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    @include('profile.partials.update-password-form')
                </div>

                {{-- Excluir Conta --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>

        </div>
    </div>
</x-app-layout>