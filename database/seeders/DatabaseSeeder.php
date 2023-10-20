<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // \App\Models\User::factory()->create([
        //     'name' => 'Administrator',
        //     'email' => 'admin@gmail.com',
        //     'role' => 'ADMIN',
        //     'is_active' => 1,
        // ]);

        for ($i = 0; $i < 10; $i++) {
            \App\Models\User::factory()->create();
            sleep(1);
        }
    }
}
