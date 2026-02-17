<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\TimeEntry;
use App\Models\User;
use App\Support\TagFormatter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TimeEntryService
{
    public function startTimer(User $user, array $payload): TimeEntry
    {
        return DB::transaction(function () use ($user, $payload): TimeEntry {
            $running = $user->timeEntries()->running()->lockForUpdate()->first();

            if ($running) {
                throw ValidationException::withMessages([
                    'timer' => 'Es läuft bereits ein Timer. Bitte zuerst stoppen.',
                ]);
            }

            return $user->timeEntries()->create([
                'project_id' => (int) $payload['project_id'],
                'task_label' => $payload['task_label'] ?: null,
                'description' => trim((string) $payload['description']),
                'start_at' => now(),
                'is_running' => true,
                'tags_json' => TagFormatter::parse($payload['tags'] ?? null),
            ]);
        });
    }

    public function stopTimer(User $user): TimeEntry
    {
        return DB::transaction(function () use ($user): TimeEntry {
            $running = $user->timeEntries()->running()->lockForUpdate()->first();

            if (! $running) {
                throw ValidationException::withMessages([
                    'timer' => 'Es ist kein laufender Timer vorhanden.',
                ]);
            }

            $endAt = now();

            $running->update([
                'end_at' => $endAt,
                'duration_minutes' => $this->durationInMinutes($running->start_at, $endAt),
                'is_running' => false,
            ]);

            return $running->refresh();
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function normalizeManualPayload(User $user, array $payload): array
    {
        $startAt = $this->parseInUserTimezone($user, (string) $payload['start_at']);

        $endAt = null;

        if (! empty($payload['end_at'])) {
            $endAt = $this->parseInUserTimezone($user, (string) $payload['end_at']);
        }

        if (! $endAt && ! empty($payload['duration_minutes'])) {
            $endAt = (clone $startAt)->addMinutes((int) $payload['duration_minutes']);
        }

        if (! $endAt || $endAt->lessThanOrEqualTo($startAt)) {
            throw ValidationException::withMessages([
                'end_at' => 'Die Endzeit muss nach der Startzeit liegen.',
            ]);
        }

        return [
            'project_id' => (int) $payload['project_id'],
            'task_label' => $payload['task_label'] ?: null,
            'description' => trim((string) $payload['description']),
            'start_at' => $startAt->utc(),
            'end_at' => $endAt->utc(),
            'duration_minutes' => $this->durationInMinutes($startAt, $endAt),
            'is_running' => false,
            'tags_json' => TagFormatter::parse($payload['tags'] ?? null),
        ];
    }

    public function durationInMinutes(Carbon $startAt, Carbon $endAt): int
    {
        return max(1, (int) $startAt->diffInMinutes($endAt));
    }

    public function roundedDuration(int $rawMinutes, int $roundingStep): int
    {
        if ($roundingStep <= 1) {
            return $rawMinutes;
        }

        return (int) (ceil($rawMinutes / $roundingStep) * $roundingStep);
    }

    public function resolveSetting(User $user): Setting
    {
        return $user->setting()->firstOrCreate([], [
            'rounding_minutes' => 15,
            'week_start' => 'Mon',
            'timezone' => 'UTC',
        ]);
    }

    public function parseInUserTimezone(User $user, string $value): Carbon
    {
        $setting = $this->resolveSetting($user);

        return Carbon::parse($value, $setting->timezone);
    }
}
