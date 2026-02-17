<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(5),
            'content_markdown' => '## '.fake()->words(3, true)."\n\n".fake()->paragraph(),
            'is_favorite' => fake()->boolean(20),
            'tags_json' => fake()->randomElements(['php', 'laravel', 'debug', 'sql', 'notes'], fake()->numberBetween(1, 3)),
        ];
    }
}
