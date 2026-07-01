@props(['post' => null, 'buttonText' => 'Publicar', 'inline' => false, 'maxBody' => 1200])
@csrf

<div x-data="{ body: @js(old('body', $post->body ?? '')), max: {{ $maxBody }} }">
    <div>
        <x-input-label for="title" value="Título" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full rounded-2xl" :value="old('title', $post->title ?? '')" required autofocus maxlength="255" />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

    <div class="mt-4">
        <div class="flex items-center justify-between gap-3">
            <x-input-label for="body" value="Conteúdo" />
            <span class="text-xs" :class="body.length >= max ? 'text-red-500' : 'text-gray-400'">
                <span x-text="max - body.length"></span> / <span x-text="max"></span>
            </span>
        </div>
        <textarea
            id="body"
            name="body"
            rows="10"
            required
            minlength="10"
            maxlength="{{ $maxBody }}"
            :maxlength="max"
            x-model="body"
            @input="if (body.length > max) body = body.slice(0, max)"
            class="mt-1 block w-full resize-none rounded-2xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            :class="body.length >= max ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
            placeholder="Compartilhe uma ideia, atualização ou aprendizado..."
        ></textarea>
        <x-input-error class="mt-2" :messages="$errors->get('body')" />
    </div>

    <div class="mt-6 flex items-center gap-4">
        <x-primary-button>{{ $buttonText }}</x-primary-button>
        @if (! $inline)
            <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
        @else
            <button type="button" @click="$dispatch('close-post-drawer')" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</button>
        @endif
    </div>
</div>