<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(500, 15000), // de 5 à 150€ étant donné qu'on compte en centimes
            'image' => $this->faker->imageUrl(640, 480, 'products'),
            'status' => $this->faker->randomElement(ProductStatus::cases()),
        ];
    }
}
