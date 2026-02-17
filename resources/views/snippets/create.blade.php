<x-app-layout>
    <div class="panel p-6">
        <h1 class="text-2xl font-semibold text-slate-900">Neues Snippet</h1>

        <form method="POST" action="{{ route('snippets.store') }}" class="mt-6 space-y-6">
            @csrf
            @include('snippets._form', ['projects' => $projects, 'prefill' => $prefill])

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Snippet speichern</button>
                <a href="{{ route('snippets.index') }}" class="btn-secondary">Abbrechen</a>
            </div>
        </form>
    </div>
</x-app-layout>
