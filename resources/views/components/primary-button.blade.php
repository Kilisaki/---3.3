<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 btn-gaming font-semibold text-xs text-white uppercase tracking-widest rounded-md focus:outline-none focus:ring-2 focus:ring-imperial-red focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
