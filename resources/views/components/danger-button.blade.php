<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 btn-outline-blood-red rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blood-red active:bg-blood-red focus:outline-none focus:ring-2 focus:ring-blood-red focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
