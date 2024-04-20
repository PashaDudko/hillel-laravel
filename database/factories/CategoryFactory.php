<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->word(10);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
        ];
    }
}
