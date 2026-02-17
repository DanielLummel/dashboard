<x-app-layout>
    <div class="mx-auto max-w-3xl panel p-6">
        <h1 class="text-2xl font-semibold text-slate-900">Neues Projekt</h1>
        <p class="mt-1 text-sm text-slate-600">Lege ein Projekt an, um Notes, Snippets und Zeiten zu bündeln.</p>

        <form method="POST" action="{{ route('projects.store') }}" class="mt-6 space-y-6">
            @csrf
            @include('projects._form')

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Projekt speichern</button>
                <a href="{{ route('projects.index') }}" class="btn-secondary">Abbrechen</a>
            </div>
        </form>
    </div>
</x-app-layout>
