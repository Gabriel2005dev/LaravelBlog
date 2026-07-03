@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'bg-white rounded-full border-transparent shadow focus:outline-none focus:ring-1/2 focus:ring-pink-600 focus:border-pink-600'
    ]) }}
>
