<x-app-layout>
    <div class="space-y-6">
        <section class="panel p-6">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full" style="background-color: {{ $project->color }}"></span>
                        <h1 class="text-2xl font-semibold text-slate-900">{{ $project->name }}</h1>
                    </div>
                    @if ($project->description)
                        <p class="mt-2 max-w-3xl text-sm text-slate-600">{{ $project->description }}</p>
                    @endif
                    @if ($project->repo_url)
                        <a href="{{ $project->repo_url }}" target="_blank" class="mt-2 inline-block text-sm text-brand-700 underline dark:text-brand-400">Repository öffnen</a>
                    @endif
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('projects.edit', $project) }}" class="btn-secondary">Bearbeiten</a>
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Projekt wirklich löschen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Löschen</button>
                    </form>
                </div>
            </div>

            <div class="mt-5 flex flex-wrap gap-2 text-xs">
                <span class="badge">{{ $project->notes_count }} Notes</span>
                <span class="badge">{{ $project->snippets_count }} Snippets</span>
                <span class="badge">{{ $project->time_entries_count }} Time Entries</span>
            </div>
        </section>

        <section class="panel p-3">
            <div class="flex flex-wrap gap-2 text-sm">
                <a href="{{ route('projects.show', [$project, 'tab' => 'overview']) }}" class="rounded-lg px-3 py-2 {{ $tab === 'overview' ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">Übersicht</a>
                <a href="{{ route('projects.show', [$project, 'tab' => 'notes']) }}" class="rounded-lg px-3 py-2 {{ $tab === 'notes' ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">Notizen</a>
                <a href="{{ route('projects.show', [$project, 'tab' => 'snippets']) }}" class="rounded-lg px-3 py-2 {{ $tab === 'snippets' ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">Snippets</a>
                <a href="{{ route('projects.show', [$project, 'tab' => 'time']) }}" class="rounded-lg px-3 py-2 {{ $tab === 'time' ? 'bg-brand-50 text-brand-700 dark:bg-brand-700/20 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">Time</a>
            </div>
        </section>

        @if ($tab === 'notes')
            <section class="panel p-5">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Projekt-Notizen</h2>
                    <a href="{{ route('notes.create', ['project_ids' => [$project->id]]) }}" class="btn-secondary">Notiz hinzufügen</a>
                </div>
                <div class="space-y-3">
                    @forelse ($notes as $note)
                        <a href="{{ route('notes.show', $note) }}" class="block rounded-xl border border-slate-200 p-4 hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">
                            <p class="font-medium text-slate-900">{{ $note->title }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($note->content_markdown, 120) }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Keine Notizen zugeordnet.</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $notes?->links() }}</div>
            </section>
        @elseif ($tab === 'snippets')
            <section class="panel p-5">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Projekt-Snippets</h2>
                    <a href="{{ route('snippets.create', ['project_id' => $project->id]) }}" class="btn-secondary">Snippet hinzufügen</a>
                </div>
                <div class="space-y-3">
                    @forelse ($snippets as $snippet)
                        <a href="{{ route('snippets.show', $snippet) }}" class="block rounded-xl border border-slate-200 p-4 hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">
                            <p class="font-medium text-slate-900">{{ $snippet->title }}</p>
                            <p class="mt-1 text-xs uppercase text-slate-500">{{ $snippet->language }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Keine Snippets vorhanden.</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $snippets?->links() }}</div>
            </section>
        @elseif ($tab === 'time')
            <section class="panel p-5">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Projekt-Time-Entries</h2>
                    <a href="{{ route('time-entries.create') }}" class="btn-secondary">Eintrag hinzufügen</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500">
                                <th class="px-2 py-2">Start</th>
                                <th class="px-2 py-2">Beschreibung</th>
                                <th class="px-2 py-2">Dauer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($timeEntries as $entry)
                                <tr>
                                    <td class="px-2 py-2">{{ $entry->start_at?->format('d.m.Y H:i') }}</td>
                                    <td class="px-2 py-2">{{ $entry->description }}</td>
                                    <td class="px-2 py-2">{{ $entry->duration_minutes ? $entry->duration_minutes.' min' : 'läuft' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-2 py-4 text-center text-slate-500">Keine Einträge vorhanden.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $timeEntries?->links() }}</div>
            </section>
        @else
            <section class="grid gap-4 lg:grid-cols-2">
                <div class="panel p-5">
                    <h2 class="text-base font-semibold text-slate-900">Schnellaktionen</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ route('notes.create', ['project_ids' => [$project->id]]) }}" class="btn-secondary">Neue Notiz</a>
                        <a href="{{ route('snippets.create', ['project_id' => $project->id]) }}" class="btn-secondary">Neues Snippet</a>
                        <a href="{{ route('time-entries.create') }}" class="btn-secondary">Zeit buchen</a>
                    </div>
                </div>

                <div class="panel p-5">
                    <h2 class="text-base font-semibold text-slate-900">Letzte Time Entries</h2>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        @forelse ($recentEntries as $entry)
                            <li class="rounded-lg bg-slate-50 px-3 py-2">{{ $entry->start_at?->format('d.m H:i') }} · {{ $entry->description }}</li>
                        @empty
                            <li class="text-slate-500">Keine Einträge vorhanden.</li>
                        @endforelse
                    </ul>
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
