<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_can_not_see_users_list()
    {
        $response = $this->get('/user');
        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();
    }

    public function test_authenticated_no_admin_user_can_not_see_users_list()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')
            ->get('/user');

        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $user
            ])
            ->assertOk();
    }

    public function test_admin_user_can_see_users_list()
    {
        $user = User::factory()->create([
            'admin' => true
        ]);

        $response = $this->actingAs($user, 'web')
            ->get('user');

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

    public function test_guest_user_can_not_see_edit_user_view()
    {
        $response = $this->get('user/1/edit');

        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();
    }

    public function test_authenticated_not_admin_user_can_only_see_his_own_edit_view()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        /** his own edit profile */
        $response = $this->actingAs($user, 'web')
            ->get('user/' . $user->id . '/edit');

        $response->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $user
            ])
            ->assertOk();

        /** try to edit other user profile but gets redirected to his own profile */
        $response = $this->actingAs($user, 'web')
            ->get('user/' . $user2->id . '/edit');

        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $user
            ])
            ->assertOk();
    }

    public function test_admin_user_can_see_all_users_edit_view()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create([
            'admin' => true
        ]);

        /** user edit profile */
        $response = $this->actingAs($admin, 'web')
            ->get('user/' . $user->id . '/edit');

        $response->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $user
            ])
            ->assertOk();

        /** admin edit profile */
        $response = $this->actingAs($admin, 'web')
            ->get('user/' . $admin->id . '/edit');

        $response->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $admin
            ])
            ->assertOk();
    }

    public function test_guest_user_can_not_update_any_user_info()
    {
        $user = User::factory()->create([
            'name' => 'TestName',
            'surname' => 'TestSurname',
            'email' => 'test@gmail.com'
        ]);

        $response = $this ->put('user/' . $user->id, [
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'useremail@gmail.com',
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'useremail@gmail.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'TestName',
            'surname' => 'TestSurname',
            'email' => 'test@gmail.com'
        ]);

        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();
    }

    public function test_authenticated_not_admin_user_only_can_update_his_own_info()
    {
        $user = User::factory()->create([
            'name' => 'TestName',
            'surname' => 'TestSurname',
            'email' => 'test@gmail.com'
        ]);
        $user2 = User::factory()->create([
            'name' => 'TestName2',
            'surname' => 'TestSurname2',
            'email' => 'anothertestemail@gmail.com'
        ]);

        /** user can not edit other user info */
        $response = $this->actingAs($user,'web')
            ->put('user/' . $user2->id, [
                'name' => 'userName',
                'surname' => 'userSurname',
                'email' => 'useremail@gmail.com',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'useremail@gmail.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'TestName2',
            'surname' => 'TestSurname2',
            'email' => 'anothertestemail@gmail.com'
        ]);

        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();

        /** user can edit his own info */
        $response = $this->from('user/'. $user->id .'/edit')
            ->actingAs($user,'web')
            ->put('user/' . $user->id, [
                'name' => 'userName',
                'surname' => 'userSurname',
                'email' => 'useremail@gmail.com',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'TestName',
            'surname' => 'TestSurname',
            'email' => 'test@gmail.com'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'useremail@gmail.com',
        ]);

        $response->assertRedirect('user/'. $user->id .'/edit');
        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $user
            ])
            ->assertOk();
    }

    public function test_admin_user_can_update_every_user_info()
    {
        $user = User::factory()->create([
            'name' => 'TestName',
            'surname' => 'TestSurname',
            'email' => 'test@gmail.com'
        ]);
        $admin = User::factory()->create([
            'name' => 'TestName',
            'surname' => 'TestSurname',
            'email' => 'anothertestemail@gmail.com',
            'admin' => true
        ]);

        /** admin can edit other user info */
        $response = $this->from('user/'. $user->id .'/edit')
            ->actingAs($admin,'web')
            ->put('user/' . $user->id, [
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'useremail@gmail.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'useremail@gmail.com',
        ]);

        $response->assertRedirect('user/'. $user->id .'/edit');
        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $user
            ])
            ->assertOk();

        /** admin can edit his own info */
        $response = $this->from('user/'. $admin->id .'/edit')
            ->actingAs($admin,'web')
            ->put('user/' . $admin->id, [
                'name' => 'adminName',
                'surname' => 'adminSurname',
                'email' => 'adminemail@gmail.com',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'adminName',
            'surname' => 'adminSurname',
            'email' => 'adminemail@gmail.com',
        ]);

        $response->assertRedirect('user/'. $admin->id .'/edit');
        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $admin
            ])
            ->assertOk();
    }

    public function test_guest_user_can_not_update_any_user_password()
    {
        $user = User::factory()->create();

        $response = $this ->put('user/' . $user->id . '/password', [
            'password_old' => 'password',
            'password' => 'password2',
            'password_confirmation' => 'password2'
        ]);

        $user->refresh();
        $this->assertFalse(Hash::check('password2', $user->password));
        $this->assertTrue(Hash::check('password', $user->password));

        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();
    }

    public function test_authenticated_not_admin_user_can_update_his_own_password()
    {
        $user = User::factory()->create();

        $response = $this->from('user/'. $user->id .'/edit')
            ->actingAs($user,'web')
            ->put('user/' . $user->id . '/password', [
                'password_old' => 'password',
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword'
            ]);


        $user->refresh();
        $this->assertFalse(Hash::check('password', $user->password));
        $this->assertTrue(Hash::check('newpassword', $user->password));

        $response->assertRedirect('user/'. $user->id .'/edit');
        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $user
            ])
            ->assertOk();
    }

    public function test_authenticated_not_admin_user_can_not_update_other_user_password()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user,'web')
            ->put('user/' . $user2->id . '/password', [
                'password_old' => 'password',
                'password' => 'password2',
                'password_confirmation' => 'password2'
            ]);

        $user2->refresh();
        $this->assertFalse(Hash::check('password2', $user2->password));
        $this->assertTrue(Hash::check('password', $user2->password));

        $this->followRedirects($response)
            ->assertViewIs('reservation.create')
            ->assertOk();
    }

    public function test_admin_user_can_update_other_user_password_without_old_password()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create([
            'admin' => true
        ]);

        $response = $this->from('user/'. $user->id .'/edit')
            ->actingAs($admin,'web')
            ->put('user/' . $user->id . '/password', [
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword'
            ]);

        $user->refresh();
        $this->assertFalse(Hash::check('password', $user->password));
        $this->assertTrue(Hash::check('newpassword', $user->password));

        $response->assertRedirect('user/'. $user->id .'/edit');
        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $user
            ])
            ->assertOk();
    }

    public function test_admin_user_can_not_update_his_own_password_without_old_password()
    {
        $admin = User::factory()->create([
            'admin' => true
        ]);

        $response = $this->from('user/'. $admin->id .'/edit')
            ->actingAs($admin,'web')
            ->put('user/' . $admin->id . '/password', [
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword'
            ])
            ->assertSessionHasErrors([
                'password_old',
            ]);

        $admin->refresh();
        $this->assertTrue(Hash::check('password', $admin->password));
        $this->assertFalse(Hash::check('newpassword', $admin->password));

        $response->assertRedirect('user/'. $admin->id .'/edit');
        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $admin
            ])
            ->assertOk();
    }

    public function test_admin_user_can_update_his_own_password()
    {
        $admin = User::factory()->create([
            'admin' => true
        ]);

        $response = $this->from('user/'. $admin->id .'/edit')
            ->actingAs($admin,'web')
            ->put('user/' . $admin->id . '/password', [
                'password_old' => 'password',
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword'
            ]);

        $admin->refresh();
        $this->assertFalse(Hash::check('password', $admin->password));
        $this->assertTrue(Hash::check('newpassword', $admin->password));

        $response->assertRedirect('user/'. $admin->id .'/edit');
        $this->followRedirects($response)
            ->assertViewIs('users.profile')
            ->assertViewHas([
                'user' => $admin
            ])
            ->assertOk();
    }

    //show reservations
    //destroy
    //loginshow
    //login
    //logout

}
