<?php

namespace Database\Seeders;

use App\Models\Session;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Session::factory()->count(2)->create([
            'name' => 'Hamlet'
        ]);

        Session::factory()->count(4)->create();
    }
}
