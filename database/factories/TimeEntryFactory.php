<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::instance(fake()->dateTimeBetween('-5 days', '-1 hour'));
        $duration = fake()->numberBetween(15, 180);

        return [
            'user_id' => User::factory(),
            'project_id' => function (array $attributes) {
                return Project::factory()->create(['user_id' => $attributes['user_id']])->id;
            },
            'task_label' => fake()->optional()->randomElement(['Feature', 'Bugfix', 'Review', 'Sync']),
            'description' => fake()->sentence(),
            'start_at' => $start,
            'end_at' => $start->copy()->addMinutes($duration),
            'duration_minutes' => $duration,
            'is_running' => false,
            'tags_json' => fake()->randomElements(['meeting', 'coding', 'review'], fake()->numberBetween(1, 2)),
        ];
    }

    public function running(): static
    {
        return $this->state(fn () => [
            'end_at' => null,
            'duration_minutes' => null,
            'is_running' => true,
        ]);
    }
}
