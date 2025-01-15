<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Store;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'Trending' => $this->faker->numberBetween(0, 10000),
            'location' => $this->faker->text(),
            'description' => $this->faker->text(),
            'status' => $this->faker->boolean(),
            'category' => $this->faker->randomElement(["clothing","electronics","grocery","restaurant","beauty","furniture"]),
        ];
    }
}
