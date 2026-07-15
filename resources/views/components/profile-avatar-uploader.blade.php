@props([
    'user',
    'imageId' => 'avatarImg',
    'inputId' => 'avatarInput',
])

<div class="flex justify-center mb-6">
    <div class="relative w-32 h-32 transition-transform duration-200 hover:scale-105 group">

        <x-user-avatar
            :user="$user"
            :id="$imageId"
            size="w-32 h-32"
        />

        <label
            for="{{ $inputId }}"
            class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 transition-opacity duration-200 group-hover:opacity-100 cursor-pointer"
            aria-label="{{ __('Alterar avatar') }}"
        >

            <input
                type="file"
                id="{{ $inputId }}"
                class="hidden"
                accept="image/*"
            >

            <x-lucide-pencil class="w-6 h-6 text-white" />

        </label>

    </div>
</div>