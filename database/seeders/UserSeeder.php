<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'admin' => true
        ]);

        User::factory()->create([
            'email' => 'user@gmail.com'
        ]);

        User::factory()->count(5)->create();
    }
}
