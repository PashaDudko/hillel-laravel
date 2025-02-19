<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => fake()->words(8, true),
            'SKU' => random_int(100000, 999999),
            'price' => random_int(10, 150),
            'in_stock' => random_int(5, 15),
        ];
    }
}
