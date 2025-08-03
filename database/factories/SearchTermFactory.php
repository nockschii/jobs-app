<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SearchTerm>
 */
class SearchTermFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'term' => fake()->words(rand(1, 3), true),
            'results_count' => fake()->numberBetween(0, 100),
            'user_id' => null, // Can be null for anonymous searches
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'session_id' => fake()->uuid(),
            'referer' => fake()->optional()->url(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Create a search term for a specific user
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Create a search term with specific results count
     */
    public function withResultsCount(int $count): static
    {
        return $this->state(fn(array $attributes) => [
            'results_count' => $count,
        ]);
    }

    /**
     * Create a search term for anonymous user
     */
    public function anonymous(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => null,
        ]);
    }
}
