<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    private $reservationsAlreadyDone = [];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            ...$this->assureUniqueSessionRowColumn(),
            'user_id' => User::all()->random()->id,
        ];
    }

    private function assureUniqueSessionRowColumn(): array
    {
        $this->getAlreadyDoneReservations();

        do {
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

    private function getAlreadyDoneReservations()
    {
        if (empty($this->reservationsAlreadyDone)) {
            Reservation::all()->each(function ($reservation) {
                $this->reservationsAlreadyDone[] = $reservation->stringify();
            });
        }
    }
}
