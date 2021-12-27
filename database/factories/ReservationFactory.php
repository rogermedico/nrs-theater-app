<?php

namespace Database\Factories;

use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{

    private $reservationsAlreadyDone = [];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $uniqueSessionRowColumn = $this->assureUniqueSessionRowColumn();
        return [
            'user_id' => User::all()->random()->id,
            'session_id' => $uniqueSessionRowColumn['session_id'],
            'row' => $uniqueSessionRowColumn['row'],
            'column' => $uniqueSessionRowColumn['column'],
        ];
    }

    private function assureUniqueSessionRowColumn(): array
    {
        do{
            $session = Session::all()->random()->id;
            $row = rand(1, env('THEATER_MAX_ROWS'));
            $column = rand(1, env('THEATER_MAX_COLUMNS'));
        } while(in_array($session . '-' . $row . '-' . $column, $this->reservationsAlreadyDone));

        $this->reservationsAlreadyDone[] = $session . '-' . $row . '-' . $column;

        return [
            'session_id' => $session,
            'row' => $row,
            'column' => $column,
        ];
    }

}
