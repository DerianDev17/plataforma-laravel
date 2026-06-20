<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginByUsernameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_succeeds_with_correct_username_and_password()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create([
            'username' => 'jdoeeus',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'jdoeeus',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function login_fails_with_incorrect_password()
    {
        $this->seed(PermissionsSeeder::class);

        User::factory()->create([
            'username' => 'jdoeeus',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'jdoeeus',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function login_fails_with_nonexistent_username()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent_user',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function login_authenticates_using_username_field_not_email()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create([
            'username' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => 'janedoe',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_fails_with_email_in_username_field()
    {
        $this->seed(PermissionsSeeder::class);

        User::factory()->create([
            'username' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function authenticated_user_is_redirected_from_login_page()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create([
            'username' => 'testuser',
        ]);

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/dashboard');
    }
}
