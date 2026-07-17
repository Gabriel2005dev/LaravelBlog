<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'group inline-flex items-center justify-center gap-2 px-4 py-3 bg-pink-600 rounded-full text-white uppercase text-xs font-semibold hover:bg-pink-700 transition-colors'
    ]) }}
>
    <span class="inline-flex items-center gap-2 transition-transform duration-300 group-hover:-translate-y-0.5">
        {{ $slot }}
        <x-lucide-send-horizontal class="w-4 h-4" />
    </span>
</button>