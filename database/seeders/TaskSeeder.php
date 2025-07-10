<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $manager = User::where('email', 'manager@example.com')->first();
        $user1 = User::where('email', 'user@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();

        if (!$manager || !$user1 || !$user2) {
            throw new \Exception('Required users not found. Please run UserSeeder first.');
        }

        // Create tasks
        $tasks = [];

        $tasks[] = Task::factory()
            ->forUser($user1->id)
            ->withStatus('completed')
            ->create([
                'title' => 'Complete Project Plan',
                'due_date' => now()->subDay(),
            ]);

        $tasks[] = Task::factory()
            ->forUser($user1->id)
            ->withStatus('pending')
            ->create([
                'title' => 'Develop Feature A',
                'due_date' => now()->addDays(5),
            ]);

        $tasks[] = Task::factory()
            ->forUser($user2->id)
            ->withStatus('pending')
            ->create([
                'title' => 'Test Feature A',
                'due_date' => now()->addDays(10),
            ]);

        $tasks[] = Task::factory()
            ->forUser($user2->id)
            ->withStatus('completed')
            ->create([
                'title' => 'Setup Database',
                'due_date' => now()->subDays(2),
            ]);

        // Set up dependencies
        $tasks[1]->dependencies()->attach($tasks[0]->id);
        $tasks[2]->dependencies()->attach([$tasks[0]->id, $tasks[1]->id]);
    }
}
