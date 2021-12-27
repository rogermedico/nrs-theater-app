<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function test_guest_can_not_see_users_list()
    {
        $response = $this->get('/user');
        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();

    }

    public function test_authenticated_no_admin_can_not_see_users_list()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get('/user');
        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas(['user' => $user])
            ->assertOk();

    }

    public function test_admin_can_see_users_list()
    {
        $user = User::factory()->create([
            'admin' => true
        ]);

        $response = $this->actingAs($user, 'web')->get('user');
        $response->assertViewIs('admin.users')
            ->assertOk();
    }

    public function test_create_with_bad_info_returns_correct_view_and_errors()
    {
        $userRequest = [
            'name' => 'roger',
            'surname' => 'medico',
            'email' => 'test',
            'password' => 'password',
            'password_confirmation' => 'password2'
        ];

        $response = $this->post('user', $userRequest)
            ->assertSessionHasErrors([
                'email',
                'password'
            ]);

        $this->assertEquals(0, User::count());
        $this->assertDatabaseCount('users', 0);

        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();
    }

    public function test_create_returns_correct_view_and_creates_the_user()
    {
        $response = $this->post('user', [
            'name' => 'roger',
            'surname' => 'medico',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertEquals(1, User::count());
        $this->assertDatabaseHas('users', [
            'name' => 'roger',
            'surname' => 'medico',
            'email' => 'test@gmail.com',
        ]);

        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();
    }

}
