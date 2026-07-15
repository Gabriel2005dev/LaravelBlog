<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-800 border-transparent rounded-full  font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-900']) }}>
    {{ $slot }}
</button>
