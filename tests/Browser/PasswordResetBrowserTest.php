<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * PRUEBA DE BROWSER/E2E - Simula usuario real en navegador
 * 
 * Características:
 * - Navegador real (Chrome/Firefox)
 * - JavaScript funcional
 * - Interacción visual completa
 * - Más lenta pero más realista
 */
class PasswordResetBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function usuario_puede_recuperar_contraseña_completo()
    {
        // Preparar: Usuario en BD
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'activo' => 1,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // PASO 1: Ir al formulario de recuperación
            $browser->visit('/password/reset')
                   ->assertSee('Recuperar password')
                   ->type('email', $user->email)
                   ->press('Enviar enlace de recuperación')
                   ->assertSee('Te hemos enviado un enlace');

            // PASO 2: Simular clic en enlace del email
            // (En prueba real, obtendrías el token de la BD)
            $token = \Illuminate\Support\Str::random(64);
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            // PASO 3: Ir al formulario de restablecimiento
            $browser->visit("/password/reset/{$token}")
                   ->assertSee('Cambiar password')
                   ->type('email', $user->email)
                   ->type('password', 'nuevaPassword123')
                   ->type('password_confirmation', 'nuevaPassword123')
                   ->press('Actualizar password')
                   ->assertPathIs('/login')
                   ->assertSee('Tu contraseña ha sido restablecida');

            // PASO 4: Probar login con nueva contraseña
            $browser->type('email', $user->email)
                   ->type('password', 'nuevaPassword123')
                   ->press('Acceder')
                   ->assertPathIs('/dashboard');
        });
    }

    /** @test */
    public function muestra_errores_de_validacion_visualmente()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/password/reset')
                   ->type('email', 'email-invalido')
                   ->press('Enviar enlace de recuperación')
                   ->assertSee('formato del campo email es inválido')
                   ->assertPresent('.is-invalid'); // Clase CSS de error
        });
    }
}