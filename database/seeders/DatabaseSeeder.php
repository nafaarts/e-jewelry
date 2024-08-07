<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role' => 'ADMIN',
            'is_active' => 1,
        ]);

        for ($i = 0; $i < 5; $i++) {
            \App\Models\User::factory()->create();
            sleep(0.5);
        }

        \App\Models\Price::insert([
            [
                'name' => '1 Mayam Lokal 23K',
                'carat' => '23K',
                'rate' => 99.99,
                'weight' => 3.3,
                'sell_price' => 3020000,
                'buy_price' => 3020000 - 70000,
                'cost' => 70000,
                'is_percent_cost' => 0,
                'category' => 'MAYAM',
                'remarks' => 'Sample price for Mayam Lokal 23K',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '3 Mayam Lokal 23K',
                'carat' => '23K',
                'rate' => 99.99,
                'weight' => 10,
                'sell_price' => 9060000,
                'buy_price' => 9060000 - 70000,
                'cost' => 70000,
                'is_percent_cost' => 0,
                'category' => 'MAYAM',
                'remarks' => 'Sample price for Mayam Lokal 23K',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gram Logam Mulia 24K',
                'carat' => '24K',
                'rate' => 99.99,
                'weight' => 1,
                'sell_price' => 937000,
                'buy_price' => 937000,
                'cost' => 0,
                'is_percent_cost' => 0,
                'category' => 'GRAM',
                'remarks' => 'Sample price for Gram Logam Mulia 24K',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gram Lokal 23K',
                'carat' => '23K',
                'rate' => 99.5,
                'weight' => 1,
                'sell_price' => 923000,
                'buy_price' => 923000,
                'cost' => 0,
                'is_percent_cost' => 0,
                'category' => 'GRAM',
                'remarks' => 'Sample price for Gram Lokal 23K',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gram Lokal 18K',
                'carat' => '18K',
                'rate' => 75,
                'weight' => 1,
                'sell_price' => 840000,
                'buy_price' => 840000,
                'cost' => 0,
                'is_percent_cost' => 0,
                'category' => 'GRAM',
                'remarks' => 'Sample price for Gram Lokal 18K',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gram Lokal 16K',
                'carat' => '16K',
                'rate' => 70,
                'weight' => 1,
                'sell_price' => 785000,
                'buy_price' => 785000 - 20000,
                'cost' => 20000,
                'is_percent_cost' => 0,
                'category' => 'GRAM',
                'remarks' => 'Sample price for Gram Lokal 16K',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Category seeder
        // Kalung, Gelang, Cincin, Anting, Bros, Bundel, Lainnya
        \App\Models\Category::insert([
            [
                'name' => 'Kalung',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gelang',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cincin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Anting',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bros',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bundel',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lainnya',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // call the jewelry seeder class
        $this->call(JewelrySeeder::class);
    }
}
