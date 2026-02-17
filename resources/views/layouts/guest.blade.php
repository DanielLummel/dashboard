<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Developer Multi-Tool Dashboard') }}</title>

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
        <div class="flex min-h-screen items-center justify-center px-4 py-10">
            <div class="w-full max-w-md panel p-6">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
