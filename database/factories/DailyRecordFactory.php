<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DailyRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'record_date' => $this->faker->date(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'revenue' => $this->faker->randomFloat(2, 1, 1000),
            'record_date' => $this->faker->date(),
            'product_id' => rand(1, 10),
        ];
    }
}
