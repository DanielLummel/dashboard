<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use App\Support\TagFormatter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Note::class);

        $query = $request->user()->notes()->with('projects')->latest('updated_at');

        $search = trim((string) $request->query('q', ''));
        $projectId = $request->query('project_id');
        $tag = trim((string) $request->query('tag', ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('title', 'like', '%'.$search.'%')
                    ->orWhere('content_markdown', 'like', '%'.$search.'%');
            });
        }

        if ($projectId) {
            $query->whereHas('projects', function ($builder) use ($projectId): void {
                $builder->where('projects.id', $projectId);
            });
        }

        if ($tag !== '') {
            $query->whereJsonContains('tags_json', mb_strtolower($tag));
        }

        if ($request->boolean('favorite')) {
            $query->where('is_favorite', true);
        }

        $notes = $query->paginate(12)->withQueryString();

        return view('notes.index', [
            'notes' => $notes,
            'projects' => $request->user()->projects()->orderBy('name')->get(),
            'filters' => [
                'q' => $search,
                'project_id' => $projectId,
                'tag' => $tag,
                'favorite' => $request->boolean('favorite'),
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Note::class);

        return view('notes.create', [
            'projects' => $request->user()->projects()->orderBy('name')->get(),
            'selectedProjectIds' => (array) $request->query('project_ids', []),
        ]);
    }

    public function store(StoreNoteRequest $request): RedirectResponse
    {
        $this->authorize('create', Note::class);

        $data = $request->validated();

        $note = $request->user()->notes()->create([
            'title' => $data['title'],
            'content_markdown' => $data['content_markdown'],
            'is_favorite' => (bool) ($data['is_favorite'] ?? false),
            'tags_json' => TagFormatter::parse($data['tags'] ?? null),
        ]);

        $note->projects()->sync((array) ($data['project_ids'] ?? []));

        return redirect()
            ->route('notes.show', $note)
            ->with('status', 'Notiz erstellt.');
    }

    public function show(Note $note): View
    {
        $this->authorize('view', $note);

        $note->load('projects');

        return view('notes.show', [
            'note' => $note,
            'renderedMarkdown' => Str::markdown($note->content_markdown),
            'tagString' => TagFormatter::join($note->tags_json),
        ]);
    }

    public function edit(Note $note, Request $request): View
    {
        $this->authorize('update', $note);

        $note->load('projects');

        return view('notes.edit', [
            'note' => $note,
            'projects' => $request->user()->projects()->orderBy('name')->get(),
            'tagString' => TagFormatter::join($note->tags_json),
        ]);
    }

    public function update(UpdateNoteRequest $request, Note $note): RedirectResponse
    {
        $this->authorize('update', $note);

        $data = $request->validated();

        $note->update([
            'title' => $data['title'],
            'content_markdown' => $data['content_markdown'],
            'is_favorite' => (bool) ($data['is_favorite'] ?? false),
            'tags_json' => TagFormatter::parse($data['tags'] ?? null),
        ]);

        $note->projects()->sync((array) ($data['project_ids'] ?? []));

        return redirect()
            ->route('notes.show', $note)
            ->with('status', 'Notiz aktualisiert.');
    }

    public function destroy(Note $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $note->delete();

        return redirect()
            ->route('notes.index')
            ->with('status', 'Notiz gelöscht.');
    }

    public function preview(Request $request): JsonResponse
    {
        $this->authorize('create', Note::class);

        $data = $request->validate([
            'content_markdown' => ['nullable', 'string'],
        ]);

        return response()->json([
            'html' => Str::markdown($data['content_markdown'] ?? ''),
        ]);
    }
}
