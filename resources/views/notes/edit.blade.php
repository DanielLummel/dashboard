<x-app-layout>
    <div class="panel p-6">
        <h1 class="text-2xl font-semibold text-slate-900">Notiz bearbeiten</h1>

        <form method="POST" action="{{ route('notes.update', $note) }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            @include('notes._form', [
                'note' => $note,
                'projects' => $projects,
                'tagString' => $tagString,
            ])

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Änderungen speichern</button>
                <a href="{{ route('notes.show', $note) }}" class="btn-secondary">Zurück</a>
            </div>
        </form>
    </div>
</x-app-layout>
