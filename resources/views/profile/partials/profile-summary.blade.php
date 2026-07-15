<section>

    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Gerencie suas informações pessoais e mantenha seu perfil sempre atualizado.
        </p>
    </header>

    <div class="flex flex-col items-center mt-4">

        {{-- Avatar --}}
        <div class="flex justify-center">
            <x-profile-avatar-uploader :user="$user" />
        </div>

        {{-- Informações do usuário --}}
        <div class="text-center mt-2">

            <h2 class="text-xl font-bold text-gray-900">
                {{ $user->name }}
            </h2>

            <p class="text-sm text-gray-500 break-all mt-1">
                {{ $user->email }}
            </p>

        </div>

        {{-- Estatísticas --}}
        <div class="flex items-center justify-center gap-4 mt-4">

            {{-- Posts --}}
            <div class="flex flex-col items-center">

                <div class="rounded-tl-2xl rounded-br-2xl bg-pink-900 p-3">
                    <x-lucide-file-text class="w-5 h-5 text-white"/>
                </div>

                <span class="mt-1 text-lg font-bold text-gray-900">
                    {{ method_exists($user,'posts') ? $user->posts()->count() : 0 }}
                </span>

             

            </div>

            {{-- Comentários --}}
            <div class="flex flex-col items-center">

                <div class="rounded-tl-2xl rounded-br-2xl bg-blue-900 p-3">
                    <x-lucide-message-circle class="w-5 h-5 text-white"/>
                </div>

                <span class="mt-1 text-lg font-bold text-gray-900">
                    {{ method_exists($user,'comments') ? $user->comments()->count() : 0 }}
                </span>

              

            </div>

            {{-- Curtidas --}}
            <div class="flex flex-col items-center">

                <div class="rounded-tl-2xl rounded-br-2xl bg-red-900 p-3">
                    <x-lucide-heart class="w-5 h-5 text-white"/>
                </div>

                <span class="mt-1 text-lg font-bold text-red-900">
                    {{ method_exists($user,'likedPosts') ? $user->likedPosts()->count() : 0 }}
                </span>

                
            </div>

        </div>

    </div>

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
                throw new Error('Falha ao enviar a imagem.');
            }

            const data = await response.json();

            if (!data.avatar_url) {
                throw new Error('A URL da imagem não foi retornada.');
            }

            const avatar = `${data.avatar_url}?t=${Date.now()}`;

            document
                .querySelectorAll('[data-user-avatar="{{ $user->id }}"]')
                .forEach((image) => {
                    image.src = avatar;
                });

        } catch (error) {

            console.error(error);

            alert('Não foi possível atualizar sua foto de perfil.');

        } finally {

            uploading = false;
            input.value = '';
            document.body.style.cursor = 'default';

        }

    });

});
</script>