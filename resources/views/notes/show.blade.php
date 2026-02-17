<x-app-layout>
    <div class="space-y-6">
        <section class="panel p-6">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">{{ $note->title }}</h1>
                    <div class="mt-2 flex flex-wrap gap-1">
                        @if ($note->is_favorite)
                            <span class="badge bg-amber-100 text-amber-700">Favorit</span>
                        @endif

                        @foreach (($note->tags_json ?? []) as $tag)
                            <span class="badge">#{{ $tag }}</span>
                        @endforeach
                    </div>

                    <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-500">
                        @foreach ($note->projects as $project)
                            <a href="{{ route('projects.show', $project) }}" class="rounded-full bg-slate-100 px-2 py-1">{{ $project->name }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('notes.edit', $note) }}" class="btn-secondary">Bearbeiten</a>
                    <a href="{{ route('snippets.create', ['note_id' => $note->id]) }}" class="btn-secondary">Als Snippet vorbereiten</a>
                    <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Notiz wirklich löschen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Löschen</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="panel p-6">
            <div class="prose-lite max-w-none">
                {!! $renderedMarkdown !!}
            </div>
        </section>

        <section class="panel rounded-xl border-dashed p-4 text-sm text-slate-600">
            TODO: "Aus Auswahl als Snippet speichern" direkt im Editor (Text-Selektion -> Modal -> Snippet speichern).
        </section>
    </div>
</x-app-layout>
