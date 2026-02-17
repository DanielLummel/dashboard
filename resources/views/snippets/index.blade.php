<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Snippets</h1>
                <p class="text-sm text-slate-600">Code-Bausteine für schnelle Wiederverwendung.</p>
            </div>
            <a href="{{ route('snippets.create') }}" class="btn-primary">Snippet erstellen</a>
        </div>

        <form method="GET" action="{{ route('snippets.index') }}" class="panel grid gap-3 p-4 md:grid-cols-5">
            <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Suche Titel/Code" class="input-base md:col-span-2">

            <select name="project_id" class="select-base">
                <option value="">Alle Projekte</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected((string) $filters['project_id'] === (string) $project->id)>{{ $project->name }}</option>
                @endforeach
            </select>

            <select name="language" class="select-base">
                <option value="">Alle Sprachen</option>
                @foreach ($languages as $language)
                    <option value="{{ $language }}" @selected($filters['language'] === $language)>{{ $language }}</option>
                @endforeach
            </select>

            <div class="flex items-center gap-2">
                <input type="text" name="tag" value="{{ $filters['tag'] }}" placeholder="Tag" class="input-base">
                <button type="submit" class="btn-secondary">Filter</button>
            </div>
        </form>

        <div class="space-y-3">
            @forelse ($snippets as $snippet)
                <a href="{{ route('snippets.show', $snippet) }}" class="panel block p-4 hover:bg-slate-50">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="text-base font-semibold text-slate-900">{{ $snippet->title }}</h2>
                        <div class="flex gap-2">
                            <span class="badge">{{ $snippet->language }}</span>
                            @if ($snippet->project)
                                <span class="badge">{{ $snippet->project->name }}</span>
                            @endif
                        </div>
                    </div>
                    @if ($snippet->description)
                        <p class="mt-2 text-sm text-slate-600">{{ $snippet->description }}</p>
                    @endif
                    <pre class="mt-3 max-h-36 overflow-auto rounded-lg bg-slate-900 p-3 font-mono text-xs text-slate-100">{{ \Illuminate\Support\Str::limit($snippet->code, 240) }}</pre>
                </a>
            @empty
                <div class="panel p-6 text-sm text-slate-500">Keine Snippets gefunden.</div>
            @endforelse
        </div>

        <div>{{ $snippets->links() }}</div>
    </div>
</x-app-layout>
