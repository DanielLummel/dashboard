<aside
    class="fixed inset-y-0 left-0 z-40 w-72 transform border-r border-slate-200/90 bg-white/88 shadow-xl shadow-slate-200/40 backdrop-blur-xl transition duration-200 dark:border-slate-700/80 dark:bg-slate-900/90 dark:shadow-slate-950/60 lg:static lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="flex h-full flex-col">
        <div class="border-b border-slate-200 px-5 py-5 dark:border-slate-700">
            <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight text-slate-900 dark:text-slate-100">Developer Dashboard</a>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Notizen, Snippets, Zeittracking</p>
        </div>

        <nav class="space-y-1 px-3 py-4 text-sm">
            <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}">Dashboard</a>
            <a href="{{ route('notes.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('notes.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}">Notizen</a>
            <a href="{{ route('snippets.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('snippets.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}">Snippets</a>
            <a href="{{ route('time-entries.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('time-entries.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}">Time Tracking</a>
            <a href="{{ route('projects.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('projects.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}">Projekte</a>
            <a href="{{ route('settings.edit') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('settings.*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}">Settings</a>
        </nav>

        <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-700">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Später</p>
            <div class="mt-2 space-y-1 text-sm">
                <a href="{{ route('modules.tasks') }}" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Tasks / Kanban</a>
                <a href="{{ route('modules.daily-log') }}" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Daily Log</a>
                <a href="{{ route('modules.bookmarks') }}" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Bookmarks</a>
            </div>
        </div>

        <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-700">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Projekte</p>
            <div class="mt-2 max-h-40 space-y-1 overflow-y-auto pr-2">
                @forelse (($sidebarProjects ?? []) as $sidebarProject)
                    <a href="{{ route('projects.show', $sidebarProject) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $sidebarProject->color }}"></span>
                        <span class="truncate">{{ $sidebarProject->name }}</span>
                    </a>
                @empty
                    <p class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-500 dark:bg-slate-800 dark:text-slate-400">Noch keine Projekte.</p>
                @endforelse
            </div>
        </div>

        @if (! empty($sidebarRunningTimer))
            <div class="mx-4 mb-4 mt-auto rounded-xl border border-brand-200 bg-brand-50 p-3 text-sm text-brand-800 dark:border-brand-700/50 dark:bg-brand-700/15 dark:text-brand-300">
                <p class="font-semibold">Timer läuft</p>
                <p class="mt-1">{{ $sidebarRunningTimer->project?->name }} · {{ $sidebarRunningTimer->description }}</p>
                <p class="mt-1 text-xs" data-running-timer="{{ $sidebarRunningTimer->start_at?->toIso8601String() }}">seit {{ $sidebarRunningTimer->start_at?->diffForHumans() }}</p>
            </div>
        @endif
    </div>
</aside>

<div
    class="fixed inset-0 z-30 bg-slate-900/30 lg:hidden"
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
></div>
