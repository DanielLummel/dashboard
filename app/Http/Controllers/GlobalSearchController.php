<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([
                'projects' => [],
                'notes' => [],
                'snippets' => [],
            ]);
        }

        $user = $request->user();

        $projects = $user->projects()
            ->where('name', 'like', '%'.$query.'%')
            ->orderBy('name')
            ->limit(5)
            ->get(['id', 'name', 'slug']);

        $notes = $user->notes()
            ->where(function ($builder) use ($query): void {
                $builder->where('title', 'like', '%'.$query.'%')
                    ->orWhere('content_markdown', 'like', '%'.$query.'%');
            })
            ->latest('updated_at')
            ->limit(5)
            ->get(['id', 'title']);

        $snippets = $user->snippets()
            ->where(function ($builder) use ($query): void {
                $builder->where('title', 'like', '%'.$query.'%')
                    ->orWhere('code', 'like', '%'.$query.'%');
            })
            ->latest()
            ->limit(5)
            ->get(['id', 'title', 'language']);

        return response()->json([
            'projects' => $projects,
            'notes' => $notes,
            'snippets' => $snippets,
        ]);
    }
}
