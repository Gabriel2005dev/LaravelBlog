<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
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
        'flex h-10 w-10 items-center justify-center rounded-tl-2xl rounded-br-2xl transition-all duration-200',
        'bg-pink-600 text-white shadow-lg shadow-pink-200/50' => $active,
        'text-gray-600 hover:bg-pink-50 hover:text-pink-600 hover:-translate-y-0.5' => ! $active,
    ])
>
    <x-dynamic-component
        :component="$icon"
        class="h-5 w-5"
    />
</a>
</div>