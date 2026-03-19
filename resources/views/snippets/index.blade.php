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
                <a href="{{ route('snippets.show', $snippet) }}" class="module-card block p-4 transition-all duration-200 hover:shadow-md">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $snippet->title }}</h2>
                        <div class="flex gap-1.5">
                            <span class="badge-brand">{{ $snippet->language }}</span>
                            @if ($snippet->project)
                                <span class="badge">{{ $snippet->project->name }}</span>
                            @endif
                        </div>
                    </div>
                    @if ($snippet->description)
                        <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">{{ $snippet->description }}</p>
                    @endif
                    <pre class="mt-3 max-h-32 overflow-auto rounded-lg bg-slate-950 p-3 font-mono text-xs leading-relaxed text-slate-200 dark:bg-slate-900">{{ \Illuminate\Support\Str::limit($snippet->code, 240) }}</pre>
                </a>
            @empty
                <div class="panel p-8 text-center text-sm text-slate-400 dark:text-slate-500">
                    <svg class="mx-auto mb-3 h-8 w-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    Keine Snippets gefunden.
                </div>
            @endforelse
        </div>

        <div>{{ $snippets->links() }}</div>
    </div>
</x-app-layout>
