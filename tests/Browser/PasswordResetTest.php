<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;

class PasswordResetTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un usuario de prueba
        User::factory()->create([
            'name' => 'Usuario Prueba',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
        ]);
    }

    /** @test */
    public function user_can_view_password_reset_request_form()
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.email');
    }

    /** @test */
    public function user_can_request_password_reset_with_valid_email()
    {
        $response = $this->post(route('password.send-link'), [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function user_cannot_request_password_reset_with_invalid_email()
    {
        $response = $this->post(route('password.send-link'), [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_can_view_password_reset_form()
    {
        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => $token,
            'created_at' => now(),
        ]);

        $response = $this->get(route('password.reset', $token));
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.reset');
        $response->assertViewHas('token', $token);
    }

    /** @test */
    public function user_can_reset_password_with_valid_token()
    {
        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => $token,
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('mensaje', 'Tu contraseña ha sido restablecida');
        
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
        
        // Verificar que la contraseña se actualizó
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /** @test */
    public function user_cannot_reset_password_with_invalid_token()
    {
        $token = Str::random(64);
        $invalidToken = Str::random(64);
        
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => $token,
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'token' => $invalidToken,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}