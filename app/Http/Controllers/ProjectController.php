<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Project::class);

        $projects = $request->user()->projects()
            ->withCount(['notes', 'snippets', 'timeEntries'])
            ->orderBy('name')
            ->paginate(12);

        return view('projects.index', [
            'projects' => $projects,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();
        $data['slug'] = $this->buildUniqueSlug($request->user()->id, (string) $data['name']);
        $data['color'] = $data['color'] ?? '#0f766e';

        $project = $request->user()->projects()->create($data);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Projekt erstellt.');
    }

    public function show(Project $project, Request $request): View
    {
        $this->authorize('view', $project);

        $tab = $request->query('tab', 'overview');

        $project->loadCount(['notes', 'snippets', 'timeEntries']);

        $notes = null;
        $snippets = null;
        $timeEntries = null;

        if ($tab === 'notes') {
            $notes = $project->notes()->where('user_id', $request->user()->id)->latest('updated_at')->paginate(10);
        }

        if ($tab === 'snippets') {
            $snippets = $project->snippets()->where('user_id', $request->user()->id)->latest()->paginate(10);
        }

        if ($tab === 'time') {
            $timeEntries = $project->timeEntries()->where('user_id', $request->user()->id)->latest('start_at')->paginate(10);
        }

        return view('projects.show', [
            'project' => $project,
            'tab' => $tab,
            'notes' => $notes,
            'snippets' => $snippets,
            'timeEntries' => $timeEntries,
            'recentEntries' => $project->timeEntries()->where('user_id', $request->user()->id)->latest('start_at')->take(5)->get(),
        ]);
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', [
            'project' => $project,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $data = $request->validated();

        if ($project->name !== $data['name']) {
            $data['slug'] = $this->buildUniqueSlug($request->user()->id, (string) $data['name'], $project->id);
        }

        $project->update($data);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Projekt aktualisiert.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('status', 'Projekt gelöscht.');
    }

    private function buildUniqueSlug(int $userId, string $name, ?int $ignoreProjectId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'project';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Project::query()
                ->where('user_id', $userId)
                ->where('slug', $slug)
                ->when($ignoreProjectId, fn ($query) => $query->where('id', '!=', $ignoreProjectId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
