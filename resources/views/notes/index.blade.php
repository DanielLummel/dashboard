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
                <a href="{{ route('notes.show', $note) }}" class="panel p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="line-clamp-1 text-lg font-semibold text-slate-900">{{ $note->title }}</h2>
                        @if ($note->is_favorite)
                            <span class="badge bg-amber-100 text-amber-700">Favorit</span>
                        @endif
                    </div>

                    <p class="mt-2 line-clamp-4 text-sm text-slate-600">{{ $note->content_markdown }}</p>

                    <div class="mt-3 flex flex-wrap gap-1">
                        @foreach (($note->tags_json ?? []) as $tag)
                            <span class="badge">#{{ $tag }}</span>
                        @endforeach
                    </div>

                    <div class="mt-3 text-xs text-slate-500">
                        Aktualisiert {{ $note->updated_at?->diffForHumans() }}
                    </div>
                </a>
            @empty
                <div class="panel p-6 text-sm text-slate-500 md:col-span-2 xl:col-span-3">Keine Notizen gefunden.</div>
            @endforelse
        </div>

        <div>{{ $notes->links() }}</div>
    </div>
</x-app-layout>
