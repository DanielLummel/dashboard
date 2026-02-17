@php
    $snippet = $snippet ?? null;
    $tagString = old('tags', $tagString ?? '');
@endphp

<div class="space-y-4">
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Titel</label>
            <input id="title" name="title" type="text" class="input-base" value="{{ old('title', $snippet?->title) }}" required>
        </div>
        <div>
            <label for="project_id" class="mb-1 block text-sm font-medium text-slate-700">Projekt</label>
            <select id="project_id" name="project_id" class="select-base">
                <option value="">Kein Projekt</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected((string) old('project_id', $snippet?->project_id ?? ($prefill['project_id'] ?? null)) === (string) $project->id)>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label for="language" class="mb-1 block text-sm font-medium text-slate-700">Sprache</label>
            <input id="language" name="language" type="text" class="input-base" value="{{ old('language', $snippet?->language ?? 'php') }}" required>
        </div>
        <div>
            <label for="tags" class="mb-1 block text-sm font-medium text-slate-700">Tags</label>
            <input id="tags" name="tags" type="text" class="input-base" value="{{ $tagString }}" placeholder="laravel, api, sql">
        </div>
    </div>

    <div>
        <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Beschreibung</label>
        <textarea id="description" name="description" rows="3" class="textarea-base">{{ old('description', $snippet?->description ?? ($prefill['description'] ?? null)) }}</textarea>
    </div>

    <div>
        <label for="code" class="mb-1 block text-sm font-medium text-slate-700">Code</label>
        <textarea id="code" name="code" rows="16" class="textarea-base font-mono text-sm" required>{{ old('code', $snippet?->code ?? ($prefill['code'] ?? null)) }}</textarea>
    </div>
</div>
