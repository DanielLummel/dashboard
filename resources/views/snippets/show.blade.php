<x-app-layout>
    <div class="space-y-6">
        <section class="panel p-6">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">{{ $snippet->title }}</h1>
                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                        <span class="badge">{{ $snippet->language }}</span>
                        @if ($snippet->project)
                            <a href="{{ route('projects.show', $snippet->project) }}" class="badge">{{ $snippet->project->name }}</a>
                        @endif
                        @foreach (($snippet->tags_json ?? []) as $tag)
                            <span class="badge">#{{ $tag }}</span>
                        @endforeach
                    </div>
                    @if ($snippet->description)
                        <p class="mt-3 text-sm text-slate-600">{{ $snippet->description }}</p>
                    @endif
                </div>

                <div class="flex gap-2">
                    <button type="button" class="btn-secondary" data-copy-button data-copy-source="#snippet-code">Copy</button>
                    <a href="{{ route('snippets.edit', $snippet) }}" class="btn-secondary">Bearbeiten</a>
                    <form method="POST" action="{{ route('snippets.destroy', $snippet) }}" onsubmit="return confirm('Snippet wirklich löschen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Löschen</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="panel overflow-hidden">
            <pre id="snippet-code" class="overflow-x-auto bg-slate-900 p-5 font-mono text-sm text-slate-100">{{ $snippet->code }}</pre>
        </section>
    </div>
</x-app-layout>
