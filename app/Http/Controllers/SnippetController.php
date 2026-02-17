<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSnippetRequest;
use App\Http\Requests\UpdateSnippetRequest;
use App\Models\Note;
use App\Models\Snippet;
use App\Support\TagFormatter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SnippetController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Snippet::class);

        $query = $request->user()->snippets()->with('project')->latest();

        $search = trim((string) $request->query('q', ''));
        $projectId = $request->query('project_id');
        $language = trim((string) $request->query('language', ''));
        $tag = trim((string) $request->query('tag', ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%');
            });
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($language !== '') {
            $query->where('language', $language);
        }

        if ($tag !== '') {
            $query->whereJsonContains('tags_json', mb_strtolower($tag));
        }

        $snippets = $query->paginate(12)->withQueryString();

        return view('snippets.index', [
            'snippets' => $snippets,
            'projects' => $request->user()->projects()->orderBy('name')->get(),
            'languages' => $request->user()->snippets()->select('language')->distinct()->orderBy('language')->pluck('language'),
            'filters' => [
                'q' => $search,
                'project_id' => $projectId,
                'language' => $language,
                'tag' => $tag,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Snippet::class);

        $prefillCode = null;
        $prefillDescription = null;

        if ($request->filled('note_id')) {
            $note = Note::query()
                ->where('user_id', $request->user()->id)
                ->find($request->integer('note_id'));

            if ($note) {
                $prefillCode = $note->content_markdown;
                $prefillDescription = 'TODO: Direktes Speichern einer Auswahl aus Note ist als nächster Schritt vorgesehen.';
            }
        }

        return view('snippets.create', [
            'projects' => $request->user()->projects()->orderBy('name')->get(),
            'prefill' => [
                'project_id' => $request->query('project_id'),
                'code' => $prefillCode,
                'description' => $prefillDescription,
            ],
        ]);
    }

    public function store(StoreSnippetRequest $request): RedirectResponse
    {
        $this->authorize('create', Snippet::class);

        $data = $request->validated();

        $snippet = $request->user()->snippets()->create([
            'project_id' => $data['project_id'] ?? null,
            'title' => $data['title'],
            'language' => $data['language'],
            'code' => $data['code'],
            'description' => $data['description'] ?? null,
            'tags_json' => TagFormatter::parse($data['tags'] ?? null),
        ]);

        return redirect()
            ->route('snippets.show', $snippet)
            ->with('status', 'Snippet erstellt.');
    }

    public function show(Snippet $snippet): View
    {
        $this->authorize('view', $snippet);

        $snippet->load('project');

        return view('snippets.show', [
            'snippet' => $snippet,
            'tagString' => TagFormatter::join($snippet->tags_json),
        ]);
    }

    public function edit(Snippet $snippet, Request $request): View
    {
        $this->authorize('update', $snippet);

        return view('snippets.edit', [
            'snippet' => $snippet,
            'projects' => $request->user()->projects()->orderBy('name')->get(),
            'tagString' => TagFormatter::join($snippet->tags_json),
        ]);
    }

    public function update(UpdateSnippetRequest $request, Snippet $snippet): RedirectResponse
    {
        $this->authorize('update', $snippet);

        $data = $request->validated();

        $snippet->update([
            'project_id' => $data['project_id'] ?? null,
            'title' => $data['title'],
            'language' => $data['language'],
            'code' => $data['code'],
            'description' => $data['description'] ?? null,
            'tags_json' => TagFormatter::parse($data['tags'] ?? null),
        ]);

        return redirect()
            ->route('snippets.show', $snippet)
            ->with('status', 'Snippet aktualisiert.');
    }

    public function destroy(Snippet $snippet): RedirectResponse
    {
        $this->authorize('delete', $snippet);

        $snippet->delete();

        return redirect()
            ->route('snippets.index')
            ->with('status', 'Snippet gelöscht.');
    }
}
