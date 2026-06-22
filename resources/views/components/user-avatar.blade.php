<div>
    <!-- It always seems impossible until it is done. - Nelson Mandela -->
     @props([
    'user' => null,
    'id' => null,
    'size' => 'w-9 h-9',
    'alt' => null,
])

@php
    $avatar = data_get($user, 'avatar');
    $name = data_get($user, 'name', __('Usuário'));
    $avatarUrl = $avatar
        ? asset('storage/' . $avatar)
        : 'https://ui-avatars.com/api/?name=' . urlencode($name);
@endphp

<img
    @if ($id) id="{{ $id }}" @endif
    src="{{ $avatarUrl }}"
    alt="{{ $alt ?? __('Avatar de :name', ['name' => $name]) }}"
    {{ $attributes->merge(['class' => $size . ' rounded-full object-cover border']) }}
/>
</div>