@props([
    'user',
    'imageId' => 'avatarImg',
    'inputId' => 'avatarInput',
])

<div class="flex justify-center mb-6">
    <div class="relative w-24 h-24 group">

        <x-user-avatar
            :user="$user"
            :id="$imageId"
            size="w-24 h-24"
            class="transition-transform duration-200 group-hover:scale-105"
        />

        <label
            for="{{ $inputId }}"
            class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-200 cursor-pointer"
            aria-label="{{ __('Alterar avatar') }}"
        >

            <input
                type="file"
                id="{{ $inputId }}"
                class="hidden"
                accept="image/*"
            >

            <x-lucide-pencil
                class="w-6 h-6 text-white"
            />

        </label>

    </div>
</div>