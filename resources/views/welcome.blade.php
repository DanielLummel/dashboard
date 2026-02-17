<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Developer Multi-Tool Dashboard</title>
        <script>
            (() => {
                try {
                    const storedTheme = localStorage.getItem('theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const theme = storedTheme ?? (prefersDark ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', theme === 'dark');
                } catch {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="mx-auto flex min-h-screen max-w-6xl items-center px-6 py-10">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="inline-flex rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-brand-700">Laravel + Tailwind + DDEV</p>
                    <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-900">Developer Multi-Tool Dashboard</h1>
                    <p class="mt-4 text-base text-slate-600">
                        Lokale Productivity-App für Notizen, Code-Snippets und Time Tracking. Alles läuft lokal mit SQLite oder optional PostgreSQL.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn-primary">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-secondary">Registrieren</a>
                        @endif
                    </div>
                </div>

                <div class="panel p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Module</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-700">
                        <li class="rounded-lg bg-slate-50 p-3">Notizen mit Markdown, Suche, Projekt-Filter und Favoriten</li>
                        <li class="rounded-lg bg-slate-50 p-3">Code-Snippets mit Sprache, Tags und Copy-Button</li>
                        <li class="rounded-lg bg-slate-50 p-3">Timer + manuelle Time Entries + CSV-Export mit Rundung</li>
                        <li class="rounded-lg bg-slate-50 p-3">Vorbereitung für Tasks/Kanban, Daily Log, Bookmarks</li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
