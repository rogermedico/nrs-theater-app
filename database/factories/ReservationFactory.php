<?php

namespace Database\Factories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{

    private static $reservationsAlreadyDone = [];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $uniqueSessionRowColumn = $this->assureUniqueSessionRowColumn();
        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'session_id' => $uniqueSessionRowColumn['session_id'],
            'row' => $uniqueSessionRowColumn['row'],
            'column' => $uniqueSessionRowColumn['column'],
        ];
    }

    private function assureUniqueSessionRowColumn()
    {
        do{
            $session = Session::all()->random()->id;
            $row = rand(1, env('THEATER_MAX_ROWS'));
            $column = rand(1, env('THEATER_MAX_COLUMNS'));
        } while(in_array($session . '-' . $row . '-' . $column, ReservationFactory::$reservationsAlreadyDone));

        ReservationFactory::$reservationsAlreadyDone[] = $session . '-' . $row . '-' . $column;

        return [
            'session_id' => $session,
            'row' => $row,
            'column' => $column,
        ];
    }

}
