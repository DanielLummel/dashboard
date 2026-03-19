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
                <a href="{{ route('projects.show', $project) }}" class="module-card flex flex-col p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $project->name }}</h2>
                        <span class="h-3 w-3 shrink-0 rounded-full shadow-sm" style="background-color: {{ $project->color }}"></span>
                    </div>
                    @if ($project->description)
                        <p class="mt-2 line-clamp-2 flex-1 text-sm text-slate-500 dark:text-slate-400">{{ $project->description }}</p>
                    @else
                        <div class="flex-1"></div>
                    @endif
                    <div class="mt-4 flex flex-wrap gap-1.5">
                        <span class="badge">
                            <svg class="mr-1 h-3 w-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            {{ $project->notes_count }}
                        </span>
                        <span class="badge">
                            <svg class="mr-1 h-3 w-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                            {{ $project->snippets_count }}
                        </span>
                        <span class="badge">
                            <svg class="mr-1 h-3 w-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $project->time_entries_count }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="panel p-8 text-center text-sm text-slate-400 dark:text-slate-500 md:col-span-2 xl:col-span-3">
                    <svg class="mx-auto mb-3 h-8 w-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                    Noch keine Projekte vorhanden.
                </div>
            @endforelse
        </div>

        <div>
            {{ $projects->links() }}
        </div>
    </div>
</x-app-layout>
