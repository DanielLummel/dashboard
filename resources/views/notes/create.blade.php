<x-app-layout>
    <div class="panel p-6">
        <h1 class="text-2xl font-semibold text-slate-900">Neue Notiz</h1>
        <p class="mt-1 text-sm text-slate-600">Markdown Editor mit Live-Preview und Projektzuordnung.</p>

        <form method="POST" action="{{ route('notes.store') }}" class="mt-6 space-y-6">
            @csrf
            @include('notes._form', [
                'projects' => $projects,
                'selectedProjectIds' => $selectedProjectIds,
            ])

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Notiz speichern</button>
                <a href="{{ route('notes.index') }}" class="btn-secondary">Abbrechen</a>
            </div>
        </form>
    </div>
</x-app-layout>
