<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Ariel',
            'email' => 'ariel@example.com',
            'password' => bcrypt('12345678'),
        ]);

        \App\Models\Category::factory(10)->create();

        \App\Models\Post::factory(100)->create();

        \App\Models\Tag::factory(20)->create();

    }
}
