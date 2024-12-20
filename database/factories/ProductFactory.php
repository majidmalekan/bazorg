<?php

namespace Database\Factories;

use App\Enum\ProductStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
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
        return [
            'title' => fn () => fake()->words(7, true),
            'description' => fn () => fake()->text(191),
            'sub_label' => fn () => fake()->text(50),
            'slug' => fn ($attributes) => Str::slug($attributes['title']),
            'sku' => fn () => strtoupper(fake()->unique()->bothify('??###')),
            'status'=>ProductStatusEnum::Approved
        ];
    }
}
