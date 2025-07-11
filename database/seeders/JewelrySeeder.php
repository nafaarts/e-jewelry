<?php

namespace Database\Seeders;

use App\Models\Jewelry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JewelrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            Jewelry::factory()->create();
            sleep(0.5);
        }
    }
}
