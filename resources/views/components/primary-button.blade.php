<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg bg-gradient-to-r from-brand-700 to-brand-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm hover:from-brand-600 hover:to-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
