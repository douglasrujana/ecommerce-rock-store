<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario de prueba activo para el login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
        ]);
    }

    /**
     * Prueba de Característica: El usuario puede iniciar sesión con credenciales válidas.
     * Flujo: Accede a la página de login, extrae el token CSRF, envía credenciales válidas y verifica la autenticación y redirección.
     */
    public function test_user_can_login_with_valid_credentials()
    {
        // 1. Acceder a la página de login para obtener el token CSRF
        $response = $this->get(route('login')); // Usar $this->get()
        $response->assertStatus(200);

        // Generar un token CSRF manualmente para la prueba
        $csrfToken = csrf_token(); // Usar el helper global csrf_token()

        // 2. Intentar iniciar sesión con credenciales válidas y el token CSRF
        $postResponse = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => 'password123',
            '_token' => $csrfToken,
        ]);

        // 3. Aserciones
        if ($postResponse->status() === 419) {
            $this->fail('Received 419 Page Expired. Response content: ' . $postResponse->getContent());
        }
        $postResponse->assertRedirect(route('dashboard')); // Asumiendo que redirige al dashboard
        $this->assertAuthenticated(); // Verificar que el usuario está autenticado
    }

    /**
     * Prueba de Característica: El usuario no puede iniciar sesión con credenciales inválidas.
     * Flujo: Accede a la página de login, extrae el token CSRF, envía credenciales inválidas y verifica que no hay autenticación y hay errores de sesión.
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        // 1. Acceder a la página de login para obtener el token CSRF
        $response = $this->get(route('login')); // Usar $this->get()
        $response->assertStatus(200);

        // Generar un token CSRF manualmente para la prueba
        $csrfToken = csrf_token(); // Usar el helper global csrf_token()

        // 2. Intentar iniciar sesión con credenciales inválidas y el token CSRF
        $postResponse = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
            '_token' => $csrfToken,
        ]);

        // 3. Aserciones
        $postResponse->assertSessionHasErrors('email'); // O el campo que Laravel use para errores de login
        $this->assertGuest(); // Usar $this->assertGuest()
    }
}