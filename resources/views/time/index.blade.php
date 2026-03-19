<x-app-layout>
    @php
        $formatMinutes = static fn (int $minutes): string => sprintf('%dh %02dm', intdiv($minutes, 60), $minutes % 60);
        $timezone = $setting->timezone;
    @endphp

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Time Tracking</h1>
                <p class="text-sm text-slate-600">Timer, manuelle Einträge, Rundung und CSV-Export.</p>
            </div>
            <a href="{{ route('time-entries.create') }}" class="btn-primary">Manuellen Eintrag erstellen</a>
        </div>

        <section class="grid gap-4 lg:grid-cols-2">
            <div class="panel p-5">
                <h2 class="text-base font-semibold text-slate-900">Timer</h2>

                @if ($runningTimer)
                    <div class="mt-3 rounded-xl border border-brand-200 bg-brand-50 p-4 dark:border-brand-700/50 dark:bg-brand-700/15">
                        <p class="text-sm font-semibold text-brand-800 dark:text-brand-300">Aktiv: {{ $runningTimer->project?->name }}</p>
                        <p class="mt-1 text-sm text-brand-700 dark:text-brand-300">{{ $runningTimer->description }}</p>
                        <p class="mt-1 text-xs text-brand-700 dark:text-brand-300" data-running-timer="{{ $runningTimer->start_at?->toIso8601String() }}">seit {{ $runningTimer->start_at?->diffForHumans() }}</p>
                        <form method="POST" action="{{ route('timer.stop') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn-primary">Timer stoppen</button>
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('timer.start') }}" class="mt-3 space-y-3">
                        @csrf
                        <select name="project_id" class="select-base" required>
                            <option value="">Projekt wählen</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="task_label" class="input-base" placeholder="Task Label (optional)">
                        <input type="text" name="tags" class="input-base" placeholder="Tags, kommagetrennt">
                        <textarea name="description" rows="2" class="textarea-base" placeholder="Beschreibung" required></textarea>
                        <button type="submit" class="btn-primary">Timer starten</button>
                    </form>
                @endif
            </div>

            <div class="panel p-5">
                <h2 class="text-base font-semibold text-slate-900">Summen</h2>
                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">Heute</p>
                        <ul class="mt-2 space-y-1.5 text-sm">
                            @forelse ($todayByProject as $row)
                                <li class="flex items-center justify-between rounded-lg bg-slate-50/80 px-3 py-2 dark:bg-slate-800/50">
                                    <span class="text-slate-700 dark:text-slate-200">{{ $row->project?->name }}</span>
                                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $formatMinutes((int) $row->total_minutes) }}</span>
                                </li>
                            @empty
                                <li class="text-slate-400 dark:text-slate-500">Keine Einträge.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">Diese Woche</p>
                        <ul class="mt-2 space-y-1.5 text-sm">
                            @forelse ($weekByProject as $row)
                                <li class="flex items-center justify-between rounded-lg bg-slate-50/80 px-3 py-2 dark:bg-slate-800/50">
                                    <span class="text-slate-700 dark:text-slate-200">{{ $row->project?->name }}</span>
                                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $formatMinutes((int) $row->total_minutes) }}</span>
                                </li>
                            @empty
                                <li class="text-slate-400 dark:text-slate-500">Keine Einträge.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="panel p-4">
            <form method="GET" action="{{ route('time-entries.index') }}" class="grid gap-3 lg:grid-cols-6">
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-wide text-slate-500">Von</label>
                    <input type="date" name="from" value="{{ $filters['from'] }}" class="input-base">
                </div>
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-wide text-slate-500">Bis</label>
                    <input type="date" name="to" value="{{ $filters['to'] }}" class="input-base">
                </div>
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-wide text-slate-500">Projekt</label>
                    <select name="project_id" class="select-base">
                        <option value="">Alle</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected((string) $filters['project_id'] === (string) $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="mb-1 block text-xs uppercase tracking-wide text-slate-500">Tags</label>
                    <input type="text" name="tags" value="{{ $filters['tags'] }}" placeholder="meeting, coding" class="input-base">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-secondary">Filtern</button>
                    <a href="{{ route('time-entries.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>

            <form method="GET" action="{{ route('time-entries.export') }}" class="mt-3 flex flex-wrap items-center gap-2">
                <input type="hidden" name="from" value="{{ $filters['from'] }}">
                <input type="hidden" name="to" value="{{ $filters['to'] }}">
                <input type="hidden" name="tags" value="{{ $filters['tags'] }}">
                @if ($filters['project_id'])
                    <input type="hidden" name="project_ids[]" value="{{ $filters['project_id'] }}">
                @endif
                <button type="submit" class="btn-primary">CSV Export</button>
                <span class="text-xs text-slate-500">Rundung: {{ $setting->rounding_minutes }} Minuten</span>
            </form>
        </section>

        <section class="panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-b border-slate-100 bg-slate-50/80 dark:border-slate-700/80 dark:bg-slate-800/40">
                        <tr>
                            <th class="th">Start</th>
                            <th class="th">Ende</th>
                            <th class="th">Projekt</th>
                            <th class="th">Beschreibung</th>
                            <th class="th">Dauer</th>
                            <th class="th">Aktion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        @forelse ($entries as $entry)
                            <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/30">
                                <td class="td text-slate-500 dark:text-slate-400">{{ $entry->start_at?->copy()->timezone($timezone)->format('d.m.Y H:i') }}</td>
                                <td class="td text-slate-500 dark:text-slate-400">{{ $entry->end_at?->copy()->timezone($timezone)->format('d.m.Y H:i') ?? '—' }}</td>
                                <td class="td font-medium text-slate-900 dark:text-slate-100">{{ $entry->project?->name ?? '—' }}</td>
                                <td class="td">
                                    <p class="text-slate-700 dark:text-slate-200">{{ $entry->description }}</p>
                                    @if ($entry->task_label)
                                        <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">{{ $entry->task_label }}</p>
                                    @endif
                                </td>
                                <td class="td">
                                    @if ($entry->duration_minutes)
                                        <span class="badge">{{ $entry->duration_minutes }} min</span>
                                    @else
                                        <span class="badge-brand">läuft</span>
                                    @endif
                                </td>
                                <td class="td">
                                    @if (! $entry->is_running)
                                        <div class="flex gap-3">
                                            <a href="{{ route('time-entries.edit', $entry) }}" class="text-sm font-medium text-brand-700 hover:underline dark:text-brand-400">Edit</a>
                                            <form method="POST" action="{{ route('time-entries.destroy', $entry) }}" onsubmit="return confirm('Eintrag löschen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-600 hover:underline dark:text-red-400">Löschen</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="badge-brand">aktiv</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-400 dark:text-slate-500">Keine Time Entries gefunden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-700/80">
                {{ $entries->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
