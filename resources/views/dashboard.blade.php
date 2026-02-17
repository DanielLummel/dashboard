<x-app-layout>
    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('notes.index') }}" class="module-card p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs uppercase tracking-wide text-slate-400">Notizen</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats['notes'] }}</p>
                <p class="mt-2 text-sm text-slate-600">Markdown + Suche + Projekte</p>
            </a>
            <a href="{{ route('snippets.index') }}" class="module-card p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs uppercase tracking-wide text-slate-400">Snippets</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats['snippets'] }}</p>
                <p class="mt-2 text-sm text-slate-600">Code-Bausteine pro Projekt</p>
            </a>
            <a href="{{ route('time-entries.index') }}" class="module-card p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs uppercase tracking-wide text-slate-400">Heute</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats['today_minutes'] }}m</p>
                <p class="mt-2 text-sm text-slate-600">Diese Woche: {{ $stats['week_minutes'] }}m</p>
            </a>
            <a href="{{ route('projects.index') }}" class="module-card p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs uppercase tracking-wide text-slate-400">Projekte</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats['projects'] }}</p>
                <p class="mt-2 text-sm text-slate-600">Projekt-Hub und Tabs</p>
            </a>
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="panel p-5 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Aktueller Status</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('notes.create') }}" class="btn-secondary">Neue Notiz</a>
                        <a href="{{ route('time-entries.create') }}" class="btn-primary">Zeit buchen</a>
                    </div>
                </div>

                @if ($runningTimer)
                    <div class="mt-4 rounded-xl border border-brand-200 bg-brand-50 p-4 dark:border-brand-700/50 dark:bg-brand-700/15">
                        <p class="font-semibold text-brand-800 dark:text-brand-300">Timer läuft</p>
                        <p class="mt-1 text-sm text-brand-700 dark:text-brand-300">{{ $runningTimer->project?->name }} · {{ $runningTimer->description }}</p>
                        <p class="mt-1 text-xs text-brand-700 dark:text-brand-300" data-running-timer="{{ $runningTimer->start_at?->toIso8601String() }}">seit {{ $runningTimer->start_at?->diffForHumans() }}</p>
                    </div>
                @else
                    <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        Aktuell läuft kein Timer.
                    </div>
                @endif

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700">Zuletzt bearbeitete Notizen</h3>
                        <ul class="mt-2 space-y-2 text-sm">
                            @forelse ($recentNotes as $note)
                                <li>
                                    <a href="{{ route('notes.show', $note) }}" class="rounded-lg px-2 py-1 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $note->title }}</a>
                                </li>
                            @empty
                                <li class="text-slate-500">Keine Notizen vorhanden.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700">Zuletzt verwendete Snippets</h3>
                        <ul class="mt-2 space-y-2 text-sm">
                            @forelse ($recentSnippets as $snippet)
                                <li>
                                    <a href="{{ route('snippets.show', $snippet) }}" class="rounded-lg px-2 py-1 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $snippet->title }}</a>
                                </li>
                            @empty
                                <li class="text-slate-500">Keine Snippets vorhanden.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="panel p-5">
                <h2 class="text-base font-semibold text-slate-900">Projekte</h2>
                <ul class="mt-3 space-y-2 text-sm">
                    @forelse ($projects as $project)
                        <li>
                            <a href="{{ route('projects.show', $project) }}" class="flex items-center gap-2 rounded-lg px-2 py-1 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $project->color }}"></span>
                                <span>{{ $project->name }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-slate-500">Noch keine Projekte.</li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section class="panel p-5">
            <h2 class="text-base font-semibold text-slate-900">Letzte Time Entries</h2>
            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500">
                            <th class="px-2 py-2">Projekt</th>
                            <th class="px-2 py-2">Beschreibung</th>
                            <th class="px-2 py-2">Start</th>
                            <th class="px-2 py-2">Dauer</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentEntries as $entry)
                            <tr>
                                <td class="px-2 py-2">{{ $entry->project?->name }}</td>
                                <td class="px-2 py-2">{{ $entry->description }}</td>
                                <td class="px-2 py-2">{{ $entry->start_at?->format('d.m.Y H:i') }}</td>
                                <td class="px-2 py-2">{{ $entry->duration_minutes ? $entry->duration_minutes.' min' : 'läuft' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-2 py-5 text-center text-slate-500">Keine Einträge vorhanden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
