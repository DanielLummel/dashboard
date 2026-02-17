<header class="sticky top-0 z-20 border-b border-slate-200/90 bg-white/80 backdrop-blur-xl dark:border-slate-700/80 dark:bg-slate-900/80">
    <div class="flex items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
        <button
            type="button"
            class="btn-secondary px-2.5 py-2 lg:hidden"
            @click="sidebarOpen = !sidebarOpen"
            aria-label="Sidebar umschalten"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="relative flex-1" data-global-search>
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
            <input
                type="search"
                class="input-base pl-10"
                placeholder="Globale Suche (Projekte, Notizen, Snippets)"
                data-search-input
                autocomplete="off"
            >
            <div class="absolute left-0 right-0 top-12 z-30 hidden rounded-xl border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-900" data-search-results></div>
        </div>

        <button
            type="button"
            class="btn-secondary hidden sm:inline-flex"
            data-theme-toggle
            aria-label="Dark Mode umschalten"
        >
            <span data-theme-label>Dark</span>
        </button>

        <div class="relative" x-data="{ open: false }">
            <button type="button" class="btn-primary" @click="open = !open" @keydown.escape.window="open = false">
                Quick Add
            </button>

            <div
                class="absolute right-0 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-900"
                x-show="open"
                x-transition
                @click.outside="open = false"
                style="display: none"
            >
                <a href="{{ route('notes.create') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">+ Notiz</a>
                <a href="{{ route('snippets.create') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">+ Snippet</a>
                <a href="{{ route('time-entries.create') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">+ Time Entry</a>
                <a href="{{ route('projects.create') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">+ Projekt</a>
            </div>
        </div>

        <div class="hidden items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 sm:flex">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-brand-700 hover:text-brand-600 dark:text-brand-500 dark:hover:text-brand-400">Logout</button>
            </form>
        </div>
    </div>
</header>
