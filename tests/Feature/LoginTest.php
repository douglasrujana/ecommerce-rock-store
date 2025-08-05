<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario de prueba para el login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
        ]);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        // 1. Acceder a la página de login para obtener el token CSRF
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // Extraer el token CSRF del HTML (campo oculto _token)
        $csrfToken = $response->css('input[name="_token"]')->attr('value');

        // 2. Intentar iniciar sesión con credenciales válidas y el token CSRF
        $postResponse = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => 'password123',
            '_token' => $csrfToken,
        ]);

        // 3. Aserciones
        $postResponse->assertRedirect(route('dashboard')); // Asumiendo que redirige al dashboard
        $this->assertAuthenticated(); // Verificar que el usuario está autenticado
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        // 1. Acceder a la página de login para obtener el token CSRF
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // Extraer el token CSRF del HTML (campo oculto _token)
        $csrfToken = $response->css('input[name="_token"]')->attr('value');

        // 2. Intentar iniciar sesión con credenciales inválidas y el token CSRF
        $postResponse = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
            '_token' => $csrfToken,
        ]);

        // 3. Aserciones
        $postResponse->assertSessionHasErrors('email'); // O el campo que Laravel use para errores de login
        $this->assertGuest(); // Verificar que el usuario no está autenticado
    }
}
