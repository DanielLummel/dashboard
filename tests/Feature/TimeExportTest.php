<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Setting;
use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_csv_export_contains_raw_and_rounded_minutes(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id, 'name' => 'Export Project']);

        Setting::factory()->create([
            'user_id' => $user->id,
            'rounding_minutes' => 15,
            'timezone' => 'UTC',
            'week_start' => 'Mon',
        ]);

        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'description' => 'Exportable work',
            'duration_minutes' => 47,
            'start_at' => Carbon::parse('2026-02-15 08:00:00', 'UTC'),
            'end_at' => Carbon::parse('2026-02-15 08:47:00', 'UTC'),
            'is_running' => false,
            'tags_json' => ['billing'],
        ]);

        $response = $this->actingAs($user)->get(route('time-entries.export', [
            'from' => '2026-02-14',
            'to' => '2026-02-16',
            'project_ids' => [$project->id],
            'tags' => 'billing',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();

        $this->assertStringContainsString('duration_minutes_raw', $content);
        $this->assertStringContainsString('duration_minutes_rounded', $content);
        $this->assertStringContainsString('Exportable work', $content);
        $this->assertStringContainsString(',47,60,', $content);
    }
}
