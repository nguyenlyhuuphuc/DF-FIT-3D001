<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name(); //nguyen van a
        $slug = Str::slug($name);//nguyen-van-a

        return [
            'name' => $name,
            'status' => $this->faker->boolean(),
            'slug' => $slug
        ];
    }
}
