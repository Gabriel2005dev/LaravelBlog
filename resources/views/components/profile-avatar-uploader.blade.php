@props([
    'user',
    'imageId' => 'avatarImg',
    'inputId' => 'avatarInput',
])

<div class="flex justify-center mb-6">
    <div class="relative w-24 h-24">
        <x-user-avatar :user="$user" :id="$imageId" size="w-24 h-24" />

        <label
            for="{{ $inputId }}"
            class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 hover:opacity-100 rounded-full cursor-pointer transition"
            aria-label="{{ __('Alterar avatar') }}"
        >
            <input type="file" id="{{ $inputId }}" class="hidden" accept="image/*">

            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536M9 11l6 6m2-2L9 11m0 0L5 15m4-4l6-6" />
            </svg>
        </label>
    </div>
</div>