<x-app-layout>
    <div class="space-y-6">

        {{-- Stat cards --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            @php
                $cards = [
                    ['href' => route('notes.index'),        'value' => $stats['notes'],           'unit' => null,  'label' => 'Notizen',       'sub' => 'Markdown · Suche', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['href' => route('snippets.index'),     'value' => $stats['snippets'],        'unit' => null,  'label' => 'Snippets',      'sub' => 'Code-Bausteine',   'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                    ['href' => route('time-entries.index'), 'value' => $stats['today_minutes'],   'unit' => 'min', 'label' => 'Heute',         'sub' => 'Woche: '.$stats['week_minutes'].' min', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['href' => route('projects.index'),     'value' => $stats['projects'],        'unit' => null,  'label' => 'Projekte',      'sub' => 'Projekt-Hub',      'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'],
                ];
            @endphp

            @foreach ($cards as $card)
                <a href="{{ $card['href'] }}" class="stat-card">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-medium uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ $card['label'] }}</p>
                        <svg class="h-4 w-4 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $card['icon'] }}" />
                        </svg>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <p class="text-4xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">{{ $card['value'] }}</p>
                        @if ($card['unit'])
                            <span class="text-sm text-slate-400 dark:text-slate-500">{{ $card['unit'] }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500">{{ $card['sub'] }}</p>
                </a>
            @endforeach
        </section>

        {{-- Status + Projects --}}
        <section class="grid gap-6 lg:grid-cols-3">
            <div class="panel p-5 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h2 class="section-heading">Aktueller Status</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('notes.create') }}" class="btn-secondary text-xs">Neue Notiz</a>
                        <a href="{{ route('time-entries.create') }}" class="btn-primary text-xs">Zeit buchen</a>
                    </div>
                </div>

                @if ($runningTimer)
                    <div class="mt-4 rounded-xl border border-brand-200 bg-gradient-to-br from-brand-50 to-teal-50/40 p-4 dark:border-brand-700/50 dark:from-brand-900/25 dark:to-teal-900/15">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2 shrink-0">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-400 opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-brand-500"></span>
                            </span>
                            <p class="text-sm font-semibold text-brand-800 dark:text-brand-300">Timer läuft</p>
                        </div>
                        <p class="mt-1 text-sm text-brand-700 dark:text-brand-300">{{ $runningTimer->project?->name }} · {{ $runningTimer->description }}</p>
                        <p class="mt-1 text-xs text-brand-600 dark:text-brand-400" data-running-timer="{{ $runningTimer->start_at?->toIso8601String() }}">seit {{ $runningTimer->start_at?->diffForHumans() }}</p>
                    </div>
                @else
                    <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-slate-50/80 p-4 text-sm text-slate-500 dark:border-slate-600 dark:bg-slate-800/50 dark:text-slate-400">
                        Kein Timer aktiv.
                        <a href="{{ route('time-entries.index') }}" class="ml-1 font-medium text-brand-600 hover:underline dark:text-brand-400">Timer starten →</a>
                    </div>
                @endif

                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Zuletzt bearbeitete Notizen</h3>
                        <ul class="mt-2 space-y-0.5">
                            @forelse ($recentNotes as $note)
                                <li>
                                    <a href="{{ route('notes.show', $note) }}" class="flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                        <svg class="h-3 w-3 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg>
                                        <span class="line-clamp-1">{{ $note->title }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="px-2.5 py-1.5 text-sm text-slate-400 dark:text-slate-500">Keine Notizen vorhanden.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Zuletzt verwendete Snippets</h3>
                        <ul class="mt-2 space-y-0.5">
                            @forelse ($recentSnippets as $snippet)
                                <li>
                                    <a href="{{ route('snippets.show', $snippet) }}" class="flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                        <svg class="h-3 w-3 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg>
                                        <span class="line-clamp-1">{{ $snippet->title }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="px-2.5 py-1.5 text-sm text-slate-400 dark:text-slate-500">Keine Snippets vorhanden.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="panel p-5">
                <div class="flex items-center justify-between">
                    <h2 class="section-heading">Projekte</h2>
                    <a href="{{ route('projects.create') }}" class="text-xs font-medium text-brand-600 hover:underline dark:text-brand-400">+ Neu</a>
                </div>
                <ul class="mt-3 space-y-0.5">
                    @forelse ($projects as $project)
                        <li>
                            <a href="{{ route('projects.show', $project) }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $project->color }}"></span>
                                <span class="truncate">{{ $project->name }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="mt-2 rounded-xl bg-slate-50/80 px-3 py-6 text-center text-sm text-slate-400 dark:bg-slate-800/50">
                            <svg class="mx-auto mb-2 h-6 w-6 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                            Noch keine Projekte.
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>

        {{-- Recent time entries --}}
        <section class="panel overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-700/80">
                <h2 class="section-heading">Letzte Time Entries</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-b border-slate-100 bg-slate-50/80 dark:border-slate-700/80 dark:bg-slate-800/40">
                        <tr>
                            <th class="th">Projekt</th>
                            <th class="th">Beschreibung</th>
                            <th class="th">Start</th>
                            <th class="th">Dauer</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        @forelse ($recentEntries as $entry)
                            <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/30">
                                <td class="td font-medium text-slate-900 dark:text-slate-100">{{ $entry->project?->name ?? '—' }}</td>
                                <td class="td text-slate-600 dark:text-slate-300">{{ $entry->description }}</td>
                                <td class="td text-slate-500 dark:text-slate-400">{{ $entry->start_at?->format('d.m.Y H:i') }}</td>
                                <td class="td">
                                    @if ($entry->duration_minutes)
                                        <span class="badge">{{ $entry->duration_minutes }} min</span>
                                    @else
                                        <span class="badge-brand">läuft</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400 dark:text-slate-500">Keine Einträge vorhanden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
