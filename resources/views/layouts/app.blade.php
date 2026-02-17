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
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <div class="lg:flex lg:min-h-screen">
                @include('layouts.sidebar')

                <div class="flex-1">
                    @include('layouts.topbar')

                    @isset($header)
                        <header class="px-4 pt-4 sm:px-6 lg:px-8">
                            <div class="panel px-5 py-4">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="px-4 py-4 sm:px-6 lg:px-8">
                        @if (session('status'))
                            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-950/40 dark:text-green-300">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
                                <p class="font-semibold">Bitte Eingaben prüfen:</p>
                                <ul class="mt-2 list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
