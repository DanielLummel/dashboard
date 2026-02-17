<x-app-layout>
    <div class="mx-auto max-w-3xl panel p-6">
        <h1 class="text-2xl font-semibold text-slate-900">Time Entry bearbeiten</h1>

        <form method="POST" action="{{ route('time-entries.update', $timeEntry) }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')
            @include('time._form', ['timeEntry' => $timeEntry, 'projects' => $projects, 'tagString' => $tagString])

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Änderungen speichern</button>
                <a href="{{ route('time-entries.index') }}" class="btn-secondary">Zurück</a>
            </div>
        </form>
    </div>
</x-app-layout>
