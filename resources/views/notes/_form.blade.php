@php
    $note = $note ?? null;
    $selectedProjectIds = old('project_ids', $selectedProjectIds ?? ($note?->projects?->pluck('id')->all() ?? []));
    $isFavorite = (bool) old('is_favorite', $note?->is_favorite ?? false);
    $tagString = old('tags', $tagString ?? '');
    $content = old('content_markdown', $note?->content_markdown ?? '');
@endphp

<div class="space-y-4">
    <div>
        <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Titel</label>
        <input id="title" name="title" type="text" class="input-base" value="{{ old('title', $note?->title) }}" required>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="md:col-span-2">
            <label for="tags" class="mb-1 block text-sm font-medium text-slate-700">Tags (kommagetrennt)</label>
            <input id="tags" name="tags" type="text" class="input-base" value="{{ $tagString }}" placeholder="php, api, debugging">
        </div>
        <div class="flex items-end">
            <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                <input type="checkbox" name="is_favorite" value="1" @checked($isFavorite)>
                Favorit
            </label>
        </div>
    </div>

    <div>
        <label for="project_ids" class="mb-1 block text-sm font-medium text-slate-700">Projekte</label>
        <select id="project_ids" name="project_ids[]" multiple class="select-base min-h-28">
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected(in_array($project->id, $selectedProjectIds))>{{ $project->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">Mehrfachauswahl mit Strg/Cmd.</p>
    </div>

    <div class="grid gap-4 lg:grid-cols-2" data-markdown-preview data-preview-endpoint="{{ route('notes.preview') }}">
        <div>
            <label for="content_markdown" class="mb-1 block text-sm font-medium text-slate-700">Markdown</label>
            <textarea id="content_markdown" name="content_markdown" rows="18" class="textarea-base font-mono text-sm" data-markdown-input required>{{ $content }}</textarea>
        </div>

        <div>
            <p class="mb-1 block text-sm font-medium text-slate-700">Preview</p>
            <div class="prose-lite min-h-[24rem] rounded-lg border border-slate-200 bg-slate-50 p-4" data-markdown-output>
                {!! \Illuminate\Support\Str::markdown($content) !!}
            </div>
        </div>
    </div>
</div>
