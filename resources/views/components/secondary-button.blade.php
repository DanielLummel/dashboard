<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-lg border border-slate-300 bg-white/90 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-700 shadow-sm hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-600 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700']) }}>
    {{ $slot }}
</button>
