<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $post->title }}</h2>
            <a href="{{ route('feed') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Voltar ao feed</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-xl bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            @include('posts.partials.card', ['post' => $post, 'expanded' => true])
        </div>
    </div>
</x-app-layout>