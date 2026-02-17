@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-brand-500 bg-brand-50 py-2 ps-3 pe-4 text-start text-base font-medium text-brand-700 transition duration-150 ease-in-out focus:border-brand-600 focus:bg-brand-100 focus:text-brand-800 focus:outline-none dark:bg-brand-700/20 dark:text-brand-400 dark:focus:bg-brand-700/30 dark:focus:text-brand-300'
            : 'block w-full border-l-4 border-transparent py-2 ps-3 pe-4 text-start text-base font-medium text-slate-600 transition duration-150 ease-in-out hover:border-slate-300 hover:bg-slate-50 hover:text-slate-800 focus:border-slate-300 focus:bg-slate-50 focus:text-slate-800 focus:outline-none dark:text-slate-300 dark:hover:border-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-100 dark:focus:border-slate-600 dark:focus:bg-slate-800 dark:focus:text-slate-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
