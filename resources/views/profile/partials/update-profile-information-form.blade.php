<section>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            

    <div>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Info Profile') }}
        </h2>

         <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your'') }}
        </p>
    </header>


    <div class="flex flex-col items-center w-full">


        {{-- Avatar --}}
        <div class="flex justify-center">
            <x-profile-avatar-uploader :user="$user" />
        </div>



        {{-- Informações do usuário --}}
        <div class=" text-center">

            <h2 class="text-2xl font-bold text-gray-900">
                {{ $user->name }}
            </h2>

            <p class="text-sm text-gray-500 break-all">
                {{ $user->email }}
            </p>
        </div>




        {{-- Estatísticas --}}
        <div class="flex items-center justify-center gap-6 mt-6">


            {{-- Posts --}}
            <div class="flex flex-col items-center">

                <div class=" rounded-tl-2xl rounded-br-2xl bg-pink-900 p-3">
                    <x-lucide-file-text class="w-5 h-5 text-white"/>
                </div>

                <div class="text-center">
                    <p class="text-lg font-bold text-gray-900">
                        {{ method_exists($user,'posts') ? $user->posts()->count() : 0 }}
                    </p>
                </div>

            </div>



            {{-- Comments --}}
            <div class="flex flex-col items-center">

                <div class=" rounded-tl-2xl rounded-br-2xl bg-blue-900 p-3">
                    <x-lucide-message-circle class="w-5 h-5 text-white"/>
                </div>

                <div class="text-center">
                    <p class="text-lg font-bold text-gray-900">
                        {{ method_exists($user,'comments') ? $user->comments()->count() : 0 }}
                    </p>
                </div>

            </div>



            {{-- Likes --}}
            <div class="flex flex-col items-center">

                <div class=" rounded-tl-2xl rounded-br-2xl bg-red-900 p-3">
                    <x-lucide-heart class="w-5 h-5 text-white"/>
                </div>

                <div class="text-center">
                    <p class="text-lg font-bold text-red-900">
                        {{ method_exists($user,'likedPosts') ? $user->likedPosts()->count() : 0 }}
                    </p>
                </div>

            </div>


        </div>


    </div>
</div>

            {{-- ===================== --}}
            {{-- Coluna Direita --}}
            {{-- ===================== --}}
            <div>

                <div class=" w-full pl-8">

                    <header>
                          <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

               

                    </header>

                    <div class="space-y-7 mt-6">
                    {{-- Nome --}}
<div>
    <x-input-label
        for="name"
        :value="__('Full Name')"
        class="font-semibold text-gray-800"
    />

    <x-text-input
        id="name"
        name="name"
        type="text"
        class="mt-3 block w-full"
        :value="old('name', $user->name)"
        required
        autofocus
        autocomplete="name"
    />

    <x-input-error
        class="mt-2"
        :messages="$errors->get('name')"
    />
</div>

{{-- Email --}}
<div>
    <x-input-label
        for="email"
        :value="__('Email Address')"
        class="font-semibold text-gray-800"
    />

    <x-text-input
        id="email"
        name="email"
        type="email"
        class="mt-3 block w-full"
        :value="old('email', $user->email)"
        required
        autocomplete="username"
    />

    <x-input-error
        class="mt-2"
        :messages="$errors->get('email')"
    />

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">

            <div class="flex gap-4">

                <div class="rounded-full bg-amber-100 p-3 h-fit">

                    <x-lucide-triangle-alert
                        class="w-5 h-5 text-amber-600"
                    />

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-amber-800">
                        {{ __('Email not verified') }}
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-amber-700">
                        {{ __('Verify your email to increase the security of your account and access all available features.') }}
                    </p>

                    <button
                        form="send-verification"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-amber-700"
                    >

                        <x-lucide-send class="w-4 h-4"/>

                        {{ __('Resend verification email') }}

                    </button>

                </div>

            </div>

        </div>

        @if (session('status') === 'verification-link-sent')

            <div class="mt-5 flex items-center gap-3 rounded-xl bg-green-50 p-4 text-green-700">

                <x-lucide-circle-check-big
                    class="w-5 h-5 flex-shrink-0"
                />

                <span class="text-sm">
                    {{ __('A new verification email has been sent successfully.') }}
                </span>

            </div>

        @endif

    @endif

</div>

{{-- Rodapé --}}
<div class="pt-6">

    <div class="flex items-center justify-between">

        @if (session('status') === 'profile-updated')

            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition.opacity
                x-init="setTimeout(() => show = false, 2500)"
                class="flex items-center gap-2 text-green-600"
            >

                <x-lucide-circle-check-big class="w-5 h-5"/>

                <span class="text-sm font-medium">
                    {{ __('Changes saved successfully.') }}
                </span>

            </div>

        @else

            <div></div>

        @endif

        <x-primary-button class="px-8 py-3">

            <x-lucide-save class="w-4 h-4 mr-2"/>

            {{ __('Save Changes') }}

        </x-primary-button>

    </div>

</div>

</div>

</div>

</div>

</form>

</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('avatarInput');

    if (!input) return;

    let uploading = false;

    input.addEventListener('change', async (event) => {

        if (uploading) return;

        const file = event.target.files[0];

        if (!file) return;

        uploading = true;

        document.body.style.cursor = 'wait';

        const formData = new FormData();

        formData.append('avatar', file);
        formData.append('_token', '{{ csrf_token() }}');

        try {

            const response = await fetch("{{ route('profile.avatar') }}", {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                },
                body: formData,
            });

            if (!response.ok) {
                throw new Error('Upload failed.');
            }

            const data = await response.json();

            if (!data.avatar_url) {
                throw new Error('Avatar URL not returned.');
            }

            const avatar = `${data.avatar_url}?t=${Date.now()}`;

            document
                .querySelectorAll('[data-user-avatar="{{ $user->id }}"]')
                .forEach((image) => {
                    image.src = avatar;
                });

        } catch (error) {

            console.error(error);

            alert('Unable to update your profile picture.');

        } finally {

            uploading = false;

            input.value = '';

            document.body.style.cursor = 'default';

        }

    });

});
</script>