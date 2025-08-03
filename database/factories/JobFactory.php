<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'department' => fake()->randomElement(['Engineering', 'Marketing', 'Sales', 'HR', 'Finance']),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'application_email' => fake()->safeEmail(),
            'application_url' => fake()->url(),
            'hours_per_week' => fake()->randomElement(['Full-time (38.5)', 'Part-time (20)', 'Part-time (32)', 'Contract']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
