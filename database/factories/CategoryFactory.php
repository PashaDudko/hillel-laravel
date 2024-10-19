<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->words(2, true);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'thumbnail' => 'storage/category/default.jpg',
        ];
    }
}
