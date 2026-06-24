@csrf

<div>
    <x-input-label for="title" value="Título" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post->title ?? '')" required autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div class="mt-4">
    <x-input-label for="body" value="Conteúdo" />
    <textarea id="body" name="body" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('body', $post->body ?? '') }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('body')" />
</div>

<div class="mt-6 flex items-center gap-4">
    <x-primary-button>{{ $buttonText }}</x-primary-button>
      @if (! ($inline ?? false))
        <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
    @else
        <button type="button" @click="editing = false" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</button>
    @endif
  
</div>