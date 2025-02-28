<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomNumber(5),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'cost_price' => $this->faker->randomFloat(2, 1, 1000),
            'selling_price' => $this->faker->randomFloat(2, 1, 1000),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
