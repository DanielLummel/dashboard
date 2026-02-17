<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Projekte</h1>
                <p class="text-sm text-slate-600">Zentrale Projektübersicht mit Modulbezug.</p>
            </div>
            <a href="{{ route('projects.create') }}" class="btn-primary">Projekt erstellen</a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="panel p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="text-lg font-semibold text-slate-900">{{ $project->name }}</h2>
                        <span class="h-3 w-3 rounded-full" style="background-color: {{ $project->color }}"></span>
                    </div>
                    @if ($project->description)
                        <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $project->description }}</p>
                    @endif
                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <span class="badge">{{ $project->notes_count }} Notes</span>
                        <span class="badge">{{ $project->snippets_count }} Snippets</span>
                        <span class="badge">{{ $project->time_entries_count }} Time</span>
                    </div>
                </a>
            @empty
                <div class="panel p-6 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                    Noch keine Projekte vorhanden.
                </div>
            @endforelse
        </div>

        <div>
            {{ $projects->links() }}
        </div>
    </div>
</x-app-layout>
