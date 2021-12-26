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
        Session::factory(2)->create([
            'name' => 'Hamlet'
        ]);
        Session::factory(4)->create();
    }
}
