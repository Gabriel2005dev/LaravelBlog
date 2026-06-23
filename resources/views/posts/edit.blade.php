<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar post</h2></x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto bg-white p-6 shadow-sm sm:rounded-lg sm:px-8">
            <form method="POST" action="{{ route('posts.update', $post) }}">
                @method('PUT')
                @include('posts._form', ['buttonText' => 'Salvar alterações'])
            </form>
        </div>
    </div>
</x-app-layout>