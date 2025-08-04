<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Enums\EmploymentType;

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
        $employmentTypes = array_map(fn($type) => $type->value, EmploymentType::cases());
        return [
            'company_id' => Company::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'department' => fake()->randomElement(['Engineering', 'Marketing', 'Sales', 'HR', 'Finance']),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'application_email' => fake()->safeEmail(),
            'application_url' => fake()->url(),
            'employment_type' => fake()->randomElement($employmentTypes),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
