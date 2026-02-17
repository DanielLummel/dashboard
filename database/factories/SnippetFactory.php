<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Snippet>
 */
class SnippetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $languages = ['php', 'js', 'sql', 'bash'];

        return [
            'user_id' => User::factory(),
            'project_id' => null,
            'title' => fake()->sentence(4),
            'language' => fake()->randomElement($languages),
            'code' => "<?php\n\n// ".fake()->sentence()."\n",
            'description' => fake()->sentence(),
            'tags_json' => fake()->randomElements(['snippet', 'helper', 'refactor', 'cli'], fake()->numberBetween(1, 3)),
        ];
    }

    public function withProject(): static
    {
        return $this->state(function (array $attributes): array {
            $project = Project::factory()->create(['user_id' => $attributes['user_id']]);

            return [
                'project_id' => $project->id,
            ];
        });
    }
}
