@props([
    'align' => 'right',
    'position' => 'bottom',
    'width' => 'full',
    'contentClasses' => 'bg-white',
])

@php
    $alignmentClasses = match ($align) {
        'left' => 'left-0 origin-top-left',
        'right' => 'right-0 origin-top-right',
        'center' => 'left-1/2 -translate-x-1/2 origin-top',
        default => 'right-0 origin-top-right',
    };

    $positionClasses = match ($position) {
    'top' => 'bottom-full mb-2',
    'right' => 'left-full bottom-0 ml-3',
    default => 'top-full mt-2',
    };

    $width = match ($width) {
        '48' => 'w-48',
        '56' => 'w-56',
        '64' => 'w-64',
        '72' => 'w-72',
        'full' => 'w-full',
        default => $width,
    };
@endphp

<div
    class="relative overflow-visible"
    x-data="{ open: false }"
    @click.outside="open = false"
    @close.stop="open = false"
>
    <div @click.stop="open = !open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ $positionClasses }} {{ $alignmentClasses }} {{ $width }} z-[9999]"
        style="display: none;"
    >
        <div class="overflow-hidder shadow rounded-xl border-gray-200 bg-white {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>