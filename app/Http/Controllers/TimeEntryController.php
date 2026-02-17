<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeEntryRequest;
use App\Http\Requests\UpdateTimeEntryRequest;
use App\Models\TimeEntry;
use App\Services\TimeEntryService;
use App\Services\TimeExportService;
use App\Support\TagFormatter;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TimeEntryController extends Controller
{
    public function index(Request $request, TimeEntryService $timeEntryService): View
    {
        $this->authorize('viewAny', TimeEntry::class);

        $user = $request->user();
        $setting = $timeEntryService->resolveSetting($user);
        $timezone = $setting->timezone;

        $filters = [
            'from' => $request->query('from'),
            'to' => $request->query('to'),
            'project_id' => $request->query('project_id'),
            'tags' => trim((string) $request->query('tags', '')),
        ];

        $entriesQuery = $user->timeEntries()->with('project')->latest('start_at');

        if ($filters['from']) {
            $from = Carbon::parse((string) $filters['from'], $timezone)->startOfDay()->utc();
            $entriesQuery->where('start_at', '>=', $from);
        }

        if ($filters['to']) {
            $to = Carbon::parse((string) $filters['to'], $timezone)->endOfDay()->utc();
            $entriesQuery->where('start_at', '<=', $to);
        }

        if ($filters['project_id']) {
            $entriesQuery->where('project_id', (int) $filters['project_id']);
        }

        $tagFilters = TagFormatter::parse($filters['tags']);

        if ($tagFilters !== []) {
            $entriesQuery->where(function (Builder $builder) use ($tagFilters): void {
                foreach ($tagFilters as $tag) {
                    $builder->orWhereJsonContains('tags_json', $tag);
                }
            });
        }

        $entries = $entriesQuery->paginate(20)->withQueryString();

        $todayStart = Carbon::now($timezone)->startOfDay()->utc();
        $todayEnd = Carbon::now($timezone)->endOfDay()->utc();
        $weekStart = Carbon::now($timezone)->startOfWeek($setting->week_start === 'Sun' ? Carbon::SUNDAY : Carbon::MONDAY)->utc();
        $weekEnd = Carbon::now($timezone)->endOfWeek($setting->week_start === 'Sun' ? Carbon::SUNDAY : Carbon::MONDAY)->utc();

        $todayByProject = $this->summaryByProject($user->id, $todayStart, $todayEnd);
        $weekByProject = $this->summaryByProject($user->id, $weekStart, $weekEnd);

        return view('time.index', [
            'entries' => $entries,
            'projects' => $user->projects()->orderBy('name')->get(),
            'runningTimer' => $user->timeEntries()->running()->with('project')->first(),
            'setting' => $setting,
            'todayByProject' => $todayByProject,
            'weekByProject' => $weekByProject,
            'filters' => $filters,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', TimeEntry::class);

        return view('time.create', [
            'projects' => $request->user()->projects()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreTimeEntryRequest $request, TimeEntryService $timeEntryService): RedirectResponse
    {
        $this->authorize('create', TimeEntry::class);

        $payload = $timeEntryService->normalizeManualPayload($request->user(), $request->validated());

        $request->user()->timeEntries()->create($payload);

        return redirect()
            ->route('time-entries.index')
            ->with('status', 'Zeiteintrag erstellt.');
    }

    public function edit(TimeEntry $timeEntry, Request $request): View
    {
        $this->authorize('update', $timeEntry);

        return view('time.edit', [
            'timeEntry' => $timeEntry,
            'projects' => $request->user()->projects()->orderBy('name')->get(),
            'tagString' => TagFormatter::join($timeEntry->tags_json),
        ]);
    }

    public function update(UpdateTimeEntryRequest $request, TimeEntry $timeEntry, TimeEntryService $timeEntryService): RedirectResponse
    {
        $this->authorize('update', $timeEntry);

        if ($timeEntry->is_running) {
            return redirect()->back()->withErrors([
                'time_entry' => 'Laufende Timer müssen über Start/Stop beendet werden.',
            ]);
        }

        $payload = $timeEntryService->normalizeManualPayload($request->user(), $request->validated());

        $timeEntry->update($payload);

        return redirect()
            ->route('time-entries.index')
            ->with('status', 'Zeiteintrag aktualisiert.');
    }

    public function destroy(TimeEntry $timeEntry): RedirectResponse
    {
        $this->authorize('delete', $timeEntry);

        $timeEntry->delete();

        return redirect()
            ->route('time-entries.index')
            ->with('status', 'Zeiteintrag gelöscht.');
    }

    public function export(Request $request, TimeExportService $exportService): StreamedResponse
    {
        $this->authorize('viewAny', TimeEntry::class);

        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'project_ids' => ['nullable', 'array'],
            'project_ids.*' => ['integer'],
            'tags' => ['nullable', 'string'],
        ]);

        return $exportService->exportCsv($request->user(), $filters);
    }

    private function summaryByProject(int $userId, Carbon $start, Carbon $end)
    {
        return TimeEntry::query()
            ->selectRaw('project_id, SUM(duration_minutes) as total_minutes')
            ->with('project:id,name,color')
            ->where('user_id', $userId)
            ->whereBetween('start_at', [$start, $end])
            ->whereNotNull('duration_minutes')
            ->groupBy('project_id')
            ->orderByDesc('total_minutes')
            ->get();
    }
}
