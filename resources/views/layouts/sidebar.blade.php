<aside
    class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-200/90 bg-white/90 shadow-xl shadow-slate-200/40 backdrop-blur-xl transition-transform duration-300 dark:border-slate-700/80 dark:bg-slate-900/90 dark:shadow-slate-950/60 lg:static lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    {{-- Brand --}}
    <div class="border-b border-slate-200/90 px-4 py-4 dark:border-slate-700/80">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md border border-brand-700/30 bg-brand-700/10 text-brand-600 dark:border-brand-500/20 dark:bg-brand-500/10 dark:text-brand-400">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
            </span>
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Dev Dashboard</p>
        </a>
    </div>

    {{-- Main navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="space-y-0.5">
            @php
                $navItems = [
                    [
                        'route'   => 'dashboard',
                        'label'   => 'Dashboard',
                        'icon'    => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                    ],
                    [
                        'route'   => 'notes.index',
                        'label'   => 'Notizen',
                        'pattern' => 'notes.*',
                        'icon'    => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    ],
                    [
                        'route'   => 'snippets.index',
                        'label'   => 'Snippets',
                        'pattern' => 'snippets.*',
                        'icon'    => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
                    ],
                    [
                        'route'   => 'time-entries.index',
                        'label'   => 'Time Tracking',
                        'pattern' => 'time-entries.*',
                        'icon'    => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'route'   => 'projects.index',
                        'label'   => 'Projekte',
                        'pattern' => 'projects.*',
                        'icon'    => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
                    ],
                    [
                        'route'   => 'settings.edit',
                        'label'   => 'Settings',
                        'pattern' => 'settings.*',
                        'icon'    => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
                    ],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs($item['pattern'] ?? $item['route']); @endphp
                <a href="{{ route($item['route']) }}" class="{{ $isActive ? 'nav-link-active' : 'nav-link-default' }}">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $item['icon'] }}" />
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Coming soon --}}
        <div class="mt-6">
            <p class="mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-600">Demnächst</p>
            <div class="space-y-0.5 opacity-60">
                <a href="{{ route('modules.tasks') }}" class="nav-link-muted">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Tasks / Kanban</span>
                </a>
                <a href="{{ route('modules.daily-log') }}" class="nav-link-muted">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Daily Log</span>
                </a>
                <a href="{{ route('modules.bookmarks') }}" class="nav-link-muted">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <span>Bookmarks</span>
                </a>
            </div>
        </div>

        {{-- Projects list --}}
        @if (!empty($sidebarProjects) && count($sidebarProjects) > 0)
            <div class="mt-6">
                <p class="mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-600">Projekte</p>
                <div class="max-h-48 space-y-0.5 overflow-y-auto pr-1">
                    @foreach ($sidebarProjects as $sidebarProject)
                        <a href="{{ route('projects.show', $sidebarProject) }}" class="nav-link-default text-[13px]">
                            <span class="h-2 w-2 shrink-0 rounded-full" style="background-color: {{ $sidebarProject->color }}"></span>
                            <span class="truncate">{{ $sidebarProject->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </nav>

    {{-- Running timer indicator --}}
    @if (! empty($sidebarRunningTimer))
        <div class="mx-3 mb-3 rounded-xl border border-brand-200/80 bg-gradient-to-br from-brand-50 to-teal-50/40 p-3 dark:border-brand-700/40 dark:from-brand-900/30 dark:to-teal-900/10">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2 shrink-0">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-brand-500"></span>
                </span>
                <p class="text-xs font-semibold text-brand-800 dark:text-brand-300">Timer läuft</p>
            </div>
            <p class="mt-1 truncate text-xs text-brand-700 dark:text-brand-400">{{ $sidebarRunningTimer->project?->name }} · {{ $sidebarRunningTimer->description }}</p>
            <p class="mt-0.5 text-[11px] text-brand-600 dark:text-brand-500" data-running-timer="{{ $sidebarRunningTimer->start_at?->toIso8601String() }}">seit {{ $sidebarRunningTimer->start_at?->diffForHumans() }}</p>
        </div>
    @endif
</aside>

{{-- Mobile backdrop --}}
<div
    class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm lg:hidden"
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
></div>
