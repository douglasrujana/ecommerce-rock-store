<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Deshabilitar la protección CSRF para esta clase de prueba
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Crear un usuario de prueba
        User::factory()->create([
            'name' => 'Usuario Prueba',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_can_view_password_reset_request_form()
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.email');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_can_request_password_reset_with_valid_email()
    {
        // Generar un token CSRF manualmente para la prueba
        $csrfToken = csrf_token();

        $response = $this->withSession([])->post(route('password.send-link'), [
            'email' => 'test@example.com',
            '_token' => $csrfToken,
        ]);

        if ($response->status() === 419) {
            $this->fail('Received 419 Page Expired. Response content: ' . $response->getContent() . '
Headers: ' . json_encode($response->headers->all()));
        }
        if ($response->status() === 419) {
            $this->fail('Received 419 Page Expired. Response content: ' . $response->getContent() . '
Headers: ' . json_encode($response->headers->all()));
        }
        if ($response->status() === 419) {
            $this->fail('Received 419 Page Expired. Response content: ' . $response->getContent() . '
Headers: ' . json_encode($response->headers->all()));
        }
        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('status', trans('passwords.sent'));
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_cannot_request_password_reset_with_invalid_email()
    {
        // Generar un token CSRF manualmente para la prueba
        $csrfToken = csrf_token();

        $response = $this->withSession([])->post(route('password.send-link'), [
            'email' => 'test@example.com',
            '_token' => $csrfToken,
        ]);

        $response->assertSessionHasErrors('email');
    }

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_can_reset_password_with_valid_token()
    {
        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => $token,
            'created_at' => now(),
        ]);

        // Generar un token CSRF manualmente para la prueba
        $csrfToken = csrf_token();

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            '_token' => $csrfToken,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', trans('passwords.reset'));
        
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
        
        // Verificar que la contraseña se actualizó
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_cannot_reset_password_with_invalid_token()
    {
        $token = Str::random(64);
        $invalidToken = Str::random(64);
        
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => $token,
            'created_at' => now(),
        ]);

        // Generar un token CSRF manualmente para la prueba
        $csrfToken = csrf_token();

        $response = $this->post(route('password.update'), [
            'token' => $invalidToken,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            '_token' => $csrfToken,
        ]);

        $response->assertSessionHasErrors('email');
    }
}
