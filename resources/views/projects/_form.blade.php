@php
    $project = $project ?? null;
@endphp

<div class="space-y-4">
    <div>
        <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
        <input id="name" name="name" type="text" class="input-base" value="{{ old('name', $project?->name) }}" required>
    </div>

    <div>
        <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Beschreibung</label>
        <textarea id="description" name="description" rows="4" class="textarea-base">{{ old('description', $project?->description) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="color" class="mb-1 block text-sm font-medium text-slate-700">Farbe</label>
            <input id="color" name="color" type="color" class="input-base h-11" value="{{ old('color', $project?->color ?? '#0f766e') }}">
        </div>
        <div>
            <label for="repo_url" class="mb-1 block text-sm font-medium text-slate-700">Repository URL</label>
            <input id="repo_url" name="repo_url" type="url" class="input-base" value="{{ old('repo_url', $project?->repo_url) }}" placeholder="https://...">
        </div>
    </div>
</div>
