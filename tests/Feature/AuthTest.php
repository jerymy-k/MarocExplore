<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // Test 1 - Register
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ahmed',
            'email' => 'ahmed@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $response->assertStatus(201)->assertJsonStructure(['token', 'user']);
    }

    // Test 2 - Register avec email existant
    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create(['email' => 'ahmed@gmail.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Ahmed',
            'email' => 'ahmed@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $response->assertStatus(422);
    }

    // Test 3 - Login
    public function test_user_can_login()
    {
        User::factory()->create([
            'email' => 'ahmed@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'ahmed@gmail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
    }

    // Test 4 - Login avec mauvais mot de passe
    public function test_user_cannot_login_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'ahmed@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'ahmed@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    // Test 5 - Me sans token
    public function test_user_cannot_access_me_without_token()
    {
        $response = $this->getJson('/api/me');
        $response->assertStatus(401);
    }
}
