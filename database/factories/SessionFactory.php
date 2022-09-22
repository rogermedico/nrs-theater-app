<?php

namespace Database\Factories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'date' => $this->faker->dateTimeBetween('now','+ 1 year'),
            'ticket_price' => $this->faker->numberBetween(10,100)
        ];
    }
}
