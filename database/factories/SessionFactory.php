<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'date' => $this->faker->dateTimeBetween('now','+ 1 year'),
            'ticket_price' => $this->faker->numberBetween(10,100)
        ];
    }
}
