<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Price;
use App\Models\SafeBox;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jewelry>
 */
class JewelryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price_id' => Price::inRandomOrder()->first(),
            'category_id' => Category::inRandomOrder()->first(),
            'supplier_id' => Supplier::inRandomOrder()->first(),
            'safe_box_id' => SafeBox::inRandomOrder()->first(),
            'name' => fake()->word(2, true),
            'jewelry_code' => generateItemNumber('jewelry'),
            'weight' => fake()->randomFloat(2, 0.01, 100),
            'cost' => fake()->randomNumber(6, true),
            'is_percent_cost' => false,
            'photo' => null,
            'status' => 'READY',
            'remarks' => fake()->sentence(),
        ];
    }
}
