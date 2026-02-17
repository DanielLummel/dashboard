<x-app-layout>
    <div class="mx-auto max-w-3xl panel p-6">
        <h1 class="text-2xl font-semibold text-slate-900">Manueller Time Entry</h1>

        <form method="POST" action="{{ route('time-entries.store') }}" class="mt-6 space-y-6">
            @csrf
            @include('time._form', ['projects' => $projects, 'tagString' => old('tags', '')])

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Speichern</button>
                <a href="{{ route('time-entries.index') }}" class="btn-secondary">Zurück</a>
            </div>
        </form>
    </div>
</x-app-layout>
