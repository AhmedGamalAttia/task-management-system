<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'completed', 'canceled']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the task is assigned to a specific user.
     *
     * @param int $userId
     * @return static
     */
    public function forUser(int $userId)
    {
        return $this->state(['user_id' => $userId]);
    }

    /**
     * Indicate that the task has a specific status.
     *
     * @param string $status
     * @return static
     */
    public function withStatus(string $status)
    {
        return $this->state(['status' => $status]);
    }
}