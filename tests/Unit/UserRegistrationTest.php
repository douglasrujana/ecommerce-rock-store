<?php

namespace Tests\Unit;

use App\Models\User;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\UserRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

// Removida la importación de Pest\Laravel\assertDatabaseHas;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Asegurarse de que el rol 'cliente' exista para las pruebas
        Role::firstOrCreate(['name' => 'cliente']);
    }

    /** @test */
    public function it_registers_a_new_user_and_assigns_client_role()
    {
        $validatedData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Mockear la solicitud (Request) para simular datos validados
        $request = $this->mock(UserRequest::class, function ($mock) use ($validatedData) {
            $mock->shouldReceive('validated')->andReturn($validatedData);
            $mock->shouldReceive('input')->andReturnUsing(function ($key) use ($validatedData) {
                return $validatedData[$key] ?? null;
            });
        });

        // Crear una instancia del controlador
        $controller = new RegisterController();

        // Llamar al método registrar
        $response = $controller->registrar($request);

        // Aserciones
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'activo' => 1,
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertTrue($user->hasRole('cliente'));
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());

        // Verificar la redirección y el mensaje de sesión
        $this->assertEquals(route('dashboard'), $response->getTargetUrl());
        $this->assertStringContainsString('Registro Exitoso. Bienvenido.', session('mensaje'));
    }
}