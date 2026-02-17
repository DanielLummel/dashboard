@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'w-full rounded-lg border border-slate-300 bg-white/90 text-slate-900 shadow-sm focus:border-brand-600 focus:ring-brand-600 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-400']) }}
>
