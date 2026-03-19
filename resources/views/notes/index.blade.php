<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Notizen</h1>
                <p class="text-sm text-slate-600">Markdown Notes mit Volltextsuche und Projektbezug.</p>
            </div>
            <a href="{{ route('notes.create') }}" class="btn-primary">Notiz erstellen</a>
        </div>

        <form method="GET" action="{{ route('notes.index') }}" class="panel grid gap-3 p-4 md:grid-cols-5">
            <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Suche Titel/Inhalt" class="input-base md:col-span-2">

            <select name="project_id" class="select-base">
                <option value="">Alle Projekte</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected((string) $filters['project_id'] === (string) $project->id)>{{ $project->name }}</option>
                @endforeach
            </select>

            <input type="text" name="tag" value="{{ $filters['tag'] }}" placeholder="Tag" class="input-base">

            <div class="flex items-center gap-2">
                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="favorite" value="1" @checked($filters['favorite'])>
                    Favoriten
                </label>
                <button type="submit" class="btn-secondary">Filter</button>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($notes as $note)
                <a href="{{ route('notes.show', $note) }}" class="module-card flex flex-col p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="line-clamp-1 text-base font-semibold text-slate-900 dark:text-slate-100">{{ $note->title }}</h2>
                        @if ($note->is_favorite)
                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">★ Favorit</span>
                        @endif
                    </div>

                    <p class="mt-2 line-clamp-3 flex-1 text-sm text-slate-500 dark:text-slate-400">{{ $note->content_markdown }}</p>

                    @if (!empty($note->tags_json))
                        <div class="mt-3 flex flex-wrap gap-1">
                            @foreach ($note->tags_json as $tag)
                                <span class="badge">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-3 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500">
                        <span>{{ $note->updated_at?->diffForHumans() }}</span>
                        @if ($note->project)
                            <span class="badge-brand">{{ $note->project->name }}</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="panel p-8 text-center text-sm text-slate-400 dark:text-slate-500 md:col-span-2 xl:col-span-3">
                    <svg class="mx-auto mb-3 h-8 w-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Keine Notizen gefunden.
                </div>
            @endforelse
        </div>

        <div>{{ $notes->links() }}</div>
    </div>
</x-app-layout>
