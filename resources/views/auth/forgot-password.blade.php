<x-guest-layout>

    <div class="mb-4 text-sm text-gray-600">

        {{ __('Esqueceu sua senha? Sem problemas. Informe seu endereço de e-mail e enviaremos um link para redefinir sua senha e escolher uma nova.') }}

    </div>



    <!-- Status da sessão -->

    <x-auth-session-status 
        class="mb-4" 
        :status="session('status')" 
    />



    <form method="POST" action="{{ route('password.email') }}">

        @csrf



        <!-- E-mail -->

        <div>

            <x-input-label 
                for="email" 
                value="E-mail"
            />


            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
            />


            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>




        <div class="flex items-center justify-end mt-4">

            <x-primary-button>

                {{ __('Enviar link para redefinir senha') }}

            </x-primary-button>

        </div>


    </form>


</x-guest-layout>