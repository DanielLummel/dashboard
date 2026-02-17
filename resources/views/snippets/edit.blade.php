<x-app-layout>
    <div class="panel p-6">
        <h1 class="text-2xl font-semibold text-slate-900">Snippet bearbeiten</h1>

        <form method="POST" action="{{ route('snippets.update', $snippet) }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')
            @include('snippets._form', ['snippet' => $snippet, 'projects' => $projects, 'tagString' => $tagString, 'prefill' => []])

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Änderungen speichern</button>
                <a href="{{ route('snippets.show', $snippet) }}" class="btn-secondary">Zurück</a>
            </div>
        </form>
    </div>
</x-app-layout>
