<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Store;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(), // Automatically create a Store for this Product
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 0, 99999999.99),
            'quantity' => $this->faker->numberBetween(0, 10000),
            'Trending' => $this->faker->numberBetween(0, 10000),
            'best_selling' => $this->faker->numberBetween(0, 10000),
            'image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'category' => $this->faker->randomElement(["clothes","electronics","food","cosmetics","furniture","accessories"]),
        ];
    }
}
