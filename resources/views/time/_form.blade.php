@php
    $timeEntry = $timeEntry ?? null;
    $timezone = auth()->user()->setting?->timezone ?? config('app.timezone');

    $startValue = old('start_at');
    if (! $startValue && $timeEntry?->start_at) {
        $startValue = $timeEntry->start_at->copy()->timezone($timezone)->format('Y-m-d\\TH:i');
    }
    if (! $startValue) {
        $startValue = now($timezone)->format('Y-m-d\\TH:i');
    }

    $endValue = old('end_at');
    if (! $endValue && $timeEntry?->end_at) {
        $endValue = $timeEntry->end_at->copy()->timezone($timezone)->format('Y-m-d\\TH:i');
    }
@endphp

<div class="space-y-4">
    <div>
        <label for="project_id" class="mb-1 block text-sm font-medium text-slate-700">Projekt</label>
        <select id="project_id" name="project_id" class="select-base" required>
            <option value="">Bitte wählen</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected((string) old('project_id', $timeEntry?->project_id) === (string) $project->id)>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label for="task_label" class="mb-1 block text-sm font-medium text-slate-700">Task Label</label>
            <input id="task_label" name="task_label" type="text" class="input-base" value="{{ old('task_label', $timeEntry?->task_label) }}" placeholder="Bugfix, Feature, Review...">
        </div>
        <div>
            <label for="tags" class="mb-1 block text-sm font-medium text-slate-700">Tags</label>
            <input id="tags" name="tags" type="text" class="input-base" value="{{ old('tags', $tagString ?? '') }}" placeholder="meeting, coding">
        </div>
    </div>

    <div>
        <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Beschreibung</label>
        <textarea id="description" name="description" rows="3" class="textarea-base" required>{{ old('description', $timeEntry?->description) }}</textarea>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label for="start_at" class="mb-1 block text-sm font-medium text-slate-700">Startzeit</label>
            <input id="start_at" name="start_at" type="datetime-local" class="input-base" value="{{ $startValue }}" required>
        </div>
        <div>
            <label for="end_at" class="mb-1 block text-sm font-medium text-slate-700">Endzeit</label>
            <input id="end_at" name="end_at" type="datetime-local" class="input-base" value="{{ $endValue }}">
        </div>
        <div>
            <label for="duration_minutes" class="mb-1 block text-sm font-medium text-slate-700">Oder Dauer (Min)</label>
            <input id="duration_minutes" name="duration_minutes" type="number" min="1" max="1440" class="input-base" value="{{ old('duration_minutes') }}">
        </div>
    </div>

    <p class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-600">
        Endzeit oder Dauer ist erforderlich. Falls beides gesetzt ist, hat Endzeit Vorrang.
    </p>
</div>
