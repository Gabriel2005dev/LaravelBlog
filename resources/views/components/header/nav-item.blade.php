@props([
    'href',
    'icon',
    'active' => false,
    'label',
])

<a
    href="{{ $href }}"
    title="{{ $label }}"
    aria-label="{{ $label }}"
    @class([
        'flex min-h-11 min-w-11 items-center justify-center rounded-tl-2xl rounded-br-2xl transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-pink-600 focus-visible:ring-offset-2',
        'bg-pink-600 text-white shadow-lg shadow-pink-200/50' => $active,
        'text-gray-600 hover:-translate-y-0.5 hover:bg-pink-50 hover:text-pink-600' => ! $active,
    ])
>
    <x-dynamic-component :component="$icon" class="h-5 w-5" />
</a>