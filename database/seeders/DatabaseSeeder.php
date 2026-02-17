<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Snippet;
use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'dev@example.test'],
            [
                'name' => 'Demo Developer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        Setting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'rounding_minutes' => 15,
                'week_start' => 'Mon',
                'timezone' => 'Europe/Berlin',
            ]
        );

        $project = Project::updateOrCreate(
            ['user_id' => $user->id, 'slug' => 'client-api'],
            [
                'name' => 'Client API',
                'description' => 'Interne API-Entwicklung inklusive Dokumentation und Deploys.',
                'color' => '#0f766e',
                'repo_url' => 'https://example.org/client-api',
            ]
        );

        $project2 = Project::updateOrCreate(
            ['user_id' => $user->id, 'slug' => 'dashboard-ui'],
            [
                'name' => 'Dashboard UI',
                'description' => 'Frontend-Prototyping und Design-System-Arbeit.',
                'color' => '#ea580c',
                'repo_url' => 'https://example.org/dashboard-ui',
            ]
        );

        $note = Note::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'Release-Checkliste'],
            [
                'content_markdown' => "## Deploy\n- Migrationen prüfen\n- Smoke-Test fahren\n\n## Rollback\n- Tag zurücksetzen\n- Feature-Flag deaktivieren",
                'is_favorite' => true,
                'tags_json' => ['release', 'ops'],
            ]
        );

        $note->projects()->sync([$project->id, $project2->id]);

        $note2 = Note::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'API-Snippets sammeln'],
            [
                'content_markdown' => "```php\nHttp::withToken(\$token)->get('/users');\n```",
                'is_favorite' => false,
                'tags_json' => ['api', 'php'],
            ]
        );

        $note2->projects()->sync([$project->id]);

        Snippet::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'Laravel HTTP Retry'],
            [
                'project_id' => $project->id,
                'language' => 'php',
                'code' => "Http::retry(3, 500)->post('/sync', ['payload' => \$data]);",
                'description' => 'Retry-Pattern für externe Requests.',
                'tags_json' => ['http', 'retry', 'laravel'],
            ]
        );

        Snippet::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'SQLite Date Filter'],
            [
                'project_id' => null,
                'language' => 'sql',
                'code' => 'SELECT * FROM time_entries WHERE start_at BETWEEN :from AND :to;',
                'description' => 'Basisabfrage für Zeitexport.',
                'tags_json' => ['sql', 'reporting'],
            ]
        );

        $start1 = Carbon::now('Europe/Berlin')->subDays(1)->setTime(9, 15)->utc();
        $end1 = $start1->copy()->addMinutes(95);

        TimeEntry::updateOrCreate(
            [
                'user_id' => $user->id,
                'project_id' => $project->id,
                'description' => 'Endpoint-Refactor und Tests',
                'start_at' => $start1,
            ],
            [
                'task_label' => 'Feature',
                'end_at' => $end1,
                'duration_minutes' => 95,
                'is_running' => false,
                'tags_json' => ['coding', 'tests'],
            ]
        );

        TimeEntry::query()
            ->where('user_id', $user->id)
            ->where('is_running', true)
            ->update([
                'is_running' => false,
                'end_at' => now()->utc(),
                'duration_minutes' => 30,
            ]);

        $start2 = Carbon::parse('2026-02-16 10:00:00', 'Europe/Berlin')->utc();

        TimeEntry::updateOrCreate(
            [
                'user_id' => $user->id,
                'project_id' => $project2->id,
                'description' => 'UI Polishing und Komponentenbau',
                'start_at' => $start2,
            ],
            [
                'task_label' => 'UI',
                'end_at' => null,
                'duration_minutes' => null,
                'is_running' => true,
                'tags_json' => ['design', 'frontend'],
            ]
        );
    }
}
