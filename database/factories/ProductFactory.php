<?php

namespace Database\Factories;

use App\Models\ProductCategory;
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
        $name = $this->faker->name();
        $slug = Str::slug($name);

        $originalPrice = $this->faker->randomFloat(2, 0, 999999);
        $discountPrice = $originalPrice / 2;

        return [
            'name' => $name,
            'slug' => $slug,
            'price' => $originalPrice,
            'discount_price' => $discountPrice,
            'short_description' => $this->faker->text(200),
            'qty' => $this->faker->randomNumber(5, false),
            'shipping' => $this->faker->text(30),
            'weight' => $this->faker->randomFloat(2, 0, 10),
            'description' => $this->faker->randomHtml(),
            'information' => $this->faker->text(200),
            'reviews' => $this->faker->text(200),
            'status' => $this->faker->boolean(),
            'image' => null,
            'product_category_id' => $this->faker->randomElement(ProductCategory::pluck('id'))
        ];
    }

}
