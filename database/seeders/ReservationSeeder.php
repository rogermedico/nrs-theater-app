<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Session;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Reservation::factory(40)->create();
    }
}
