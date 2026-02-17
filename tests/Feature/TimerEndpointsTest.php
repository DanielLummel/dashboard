<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimerEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_start_and_stop_timer_and_only_one_running_timer_exists(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('timer.start'), [
                'project_id' => $project->id,
                'description' => 'Focus Session',
                'task_label' => 'Feature',
                'tags' => 'coding',
            ])
            ->assertRedirect(route('time-entries.index'));

        $this->assertDatabaseHas('time_entries', [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'is_running' => true,
            'description' => 'Focus Session',
        ]);

        $this->actingAs($user)
            ->post(route('timer.start'), [
                'project_id' => $project->id,
                'description' => 'Second Session',
            ])
            ->assertSessionHasErrors('timer');

        $this->actingAs($user)
            ->post(route('timer.stop'))
            ->assertRedirect(route('time-entries.index'));

        $this->assertDatabaseHas('time_entries', [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'is_running' => false,
        ]);
    }
}
