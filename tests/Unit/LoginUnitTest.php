<?php

namespace Tests\Unit;

use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Importar la fachada Session
use Illuminate\Support\Facades\Redirect; // Importar la fachada Redirect
use Tests\TestCase;

class LoginUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario de prueba activo para el login
        User::factory()->create([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
        ]);

        // Crear un usuario de prueba inactivo
        User::factory()->create([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'activo' => 0,
        ]);
    }

    /**
     * Prueba unitaria: El usuario puede iniciar sesión con credenciales válidas y cuenta activa.
     * Flujo: Se simula una solicitud con credenciales válidas y se verifica la autenticación y redirección.
     */
    public function test_user_can_login_with_valid_credentials_and_active_account()
    {
        // 1. Preparar los datos de la solicitud
        $requestData = [
            'email' => 'active@example.com',
            'password' => 'password123',
            'remember' => false,
        ];

        // 2. Mockear la solicitud (Request) para simular los datos de entrada
        $request = $this->mock(Request::class, function ($mock) use ($requestData) {
            $mock->shouldReceive('validate')->andReturn($requestData); // Simula validación exitosa
            $mock->shouldReceive('only')->with(['email', 'password'])->andReturn($requestData); // Para Auth::attempt
            $mock->shouldReceive('boolean')->with('remember')->andReturn(false); // Para el remember token
            $mock->email = $requestData['email']; // Asignar propiedades para acceso directo
            $mock->password = $requestData['password'];

            // Mockear la cadena de llamadas session()->regenerate()
            $mock->shouldReceive('session->regenerate')->andReturnSelf();
        });

        // 3. Crear una instancia del controlador
        $controller = new AuthController();

        // 4. Llamar al método login
        $response = $controller->login($request);

        // 5. Aserciones
        $this->assertAuthenticatedAs(User::where('email', 'active@example.com')->first()); // Verificar que el usuario está autenticado
        $this->assertEquals(route('dashboard'), $response->getTargetUrl()); // Verificar redirección al dashboard
    }

    /**
     * Prueba unitaria: El usuario no puede iniciar sesión con credenciales inválidas.
     * Flujo: Se simula una solicitud con credenciales incorrectas y se verifica que no hay autenticación y hay errores de sesión.
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        // 1. Preparar los datos de la solicitud
        $requestData = [
            'email' => 'active@example.com',
            'password' => 'wrong-password',
        ];

        // 2. Mockear la solicitud (Request)
        $request = $this->mock(Request::class, function ($mock) use ($requestData) {
            $mock->shouldReceive('validate')->andReturn($requestData); // Simula validación exitosa
            $mock->shouldReceive('only')->with(['email', 'password'])->andReturn($requestData); // Para Auth::attempt
            $mock->shouldReceive('boolean')->with('remember')->andReturn(false);
            $mock->email = $requestData['email'];
            $mock->password = $requestData['password'];
        });

        // Mockear la fachada Redirect para controlar el comportamiento de back() y withErrors()
        Redirect::shouldReceive('back')->andReturnSelf();
        Redirect::shouldReceive('withErrors')->with(['email' => 'Las credenciales son incorrectas.'])->andReturnSelf();
        Redirect::shouldReceive('onlyInput')->with('email')->andReturn(new \Illuminate\Http\RedirectResponse(url()->previous()));


        // 3. Crear una instancia del controlador
        $controller = new AuthController();

        // 4. Llamar al método login
        $response = $controller->login($request);

        // 5. Aserciones
        $this->assertGuest(); // Verificar que el usuario no está autenticado
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(url()->previous(), $response->getTargetUrl()); // Verificar redirección a la página anterior (back())
    }

    /**
     * Prueba unitaria: El usuario no puede iniciar sesión si su cuenta está inactiva.
     * Flujo: Se simula una solicitud con credenciales válidas pero cuenta inactiva y se verifica que no hay autenticación y hay errores de sesión.
     */
    public function test_user_cannot_login_if_account_is_inactive()
    {
        // 1. Preparar los datos de la solicitud
        $requestData = [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ];

        // 2. Mockear la solicitud (Request)
        $request = $this->mock(Request::class, function ($mock) use ($requestData) {
            $mock->shouldReceive('validate')->andReturn($requestData); // Simula validación exitosa
            $mock->shouldReceive('only')->with(['email', 'password'])->andReturn($requestData); // Para Auth::attempt
            $mock->shouldReceive('boolean')->with('remember')->andReturn(false);
            $mock->email = $requestData['email'];
            $mock->password = $requestData['password'];
        });

        // Mockear la fachada Redirect para controlar el comportamiento de back() y withErrors()
        Redirect::shouldReceive('back')->andReturnSelf();
        Redirect::shouldReceive('withErrors')->with(['email' => 'Su cuenta está inactiva. Contacte al Admin.'])->andReturnSelf();
        Redirect::shouldReceive('onlyInput')->with('email')->andReturn(new \Illuminate\Http\RedirectResponse(url()->previous()));

        // 3. Crear una instancia del controlador
        $controller = new AuthController();

        // 4. Llamar al método login
        $response = $controller->login($request);

        // 5. Aserciones
        $this->assertGuest(); // Verificar que el usuario no está autenticado
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(url()->previous(), $response->getTargetUrl()); // Verificar redirección a la página anterior (back())
    }
}
