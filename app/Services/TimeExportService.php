<?php

namespace App\Services;

use App\Models\TimeEntry;
use App\Models\User;
use App\Support\TagFormatter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TimeExportService
{
    public function __construct(
        protected TimeEntryService $timeEntryService,
    ) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function exportCsv(User $user, array $filters): StreamedResponse
    {
        $setting = $this->timeEntryService->resolveSetting($user);
        $timezone = $setting->timezone;
        $rounding = $setting->rounding_minutes;
        $tagFilter = TagFormatter::parse($filters['tags'] ?? null);
        $projectIds = array_values(array_filter(array_map(
            static fn ($id): int => (int) $id,
            (array) ($filters['project_ids'] ?? [])
        )));

        $query = TimeEntry::query()
            ->with('project')
            ->where('user_id', $user->id)
            ->whereNotNull('end_at')
            ->when(! empty($filters['from']), function (Builder $builder) use ($filters, $timezone): void {
                $start = Carbon::parse((string) $filters['from'], $timezone)->startOfDay()->utc();
                $builder->where('start_at', '>=', $start);
            })
            ->when(! empty($filters['to']), function (Builder $builder) use ($filters, $timezone): void {
                $end = Carbon::parse((string) $filters['to'], $timezone)->endOfDay()->utc();
                $builder->where('start_at', '<=', $end);
            })
            ->when($projectIds !== [], function (Builder $builder) use ($projectIds): void {
                $builder->whereIn('project_id', $projectIds);
            })
            ->orderByDesc('start_at');

        $entries = $query->get()->filter(function (TimeEntry $entry) use ($tagFilter): bool {
            if ($tagFilter === []) {
                return true;
            }

            $entryTags = $entry->tags_json ?? [];

            return count(array_intersect($tagFilter, $entryTags)) > 0;
        })->values();

        $filename = 'time-export-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($entries, $timezone, $rounding): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'date',
                'project',
                'task_label',
                'description',
                'start_time',
                'end_time',
                'duration_minutes_raw',
                'duration_minutes_rounded',
                'tags',
            ]);

            foreach ($entries as $entry) {
                $start = $entry->start_at?->copy()->timezone($timezone);
                $end = $entry->end_at?->copy()->timezone($timezone);
                $raw = $entry->duration_minutes ?? 0;
                $rounded = $this->timeEntryService->roundedDuration($raw, $rounding);

                fputcsv($handle, [
                    $start?->toDateString(),
                    $entry->project?->name,
                    $entry->task_label,
                    $entry->description,
                    $start?->format('H:i'),
                    $end?->format('H:i'),
                    $raw,
                    $rounded,
                    implode('|', $entry->tags_json ?? []),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
