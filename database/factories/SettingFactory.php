<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
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
            'rounding_minutes' => fake()->randomElement([5, 10, 15, 30]),
            'week_start' => fake()->randomElement(['Mon', 'Sun']),
            'timezone' => fake()->timezone(),
        ];
    }
}
