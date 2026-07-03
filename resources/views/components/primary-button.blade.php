<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center justify-center gap-2 px-3 py-3 bg-pink-600 border-transparent rounded-tl-2xl rounded-br-2xl  font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 '
    ]) }}
>
    {{ $slot }}
</button>