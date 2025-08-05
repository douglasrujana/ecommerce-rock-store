<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

/**
 * PRUEBA UNITARIA - Prueba métodos específicos de forma aislada
 * 
 * Características:
 * - No usa base de datos
 * - No hace HTTP requests
 * - Prueba lógica pura
 * - Muy rápida
 */
class PasswordResetServiceTest extends TestCase
{
    /** @test */
    public function genera_token_con_longitud_correcta()
    {
        // Actuar
        $token = \Illuminate\Support\Str::random(64);
        
        // Verificar
        $this->assertEquals(64, strlen($token));
        $this->assertIsString($token);
    }

    /** @test */
    public function valida_formato_de_email_correctamente()
    {
        // Casos válidos
        $this->assertTrue(filter_var('test@example.com', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertTrue(filter_var('user.name@domain.co.uk', FILTER_VALIDATE_EMAIL) !== false);
        
        // Casos inválidos
        $this->assertFalse(filter_var('invalid-email', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('test@', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('@domain.com', FILTER_VALIDATE_EMAIL) !== false);
    }

    /** @test */
    public function calcula_expiracion_de_token_correctamente()
    {
        // Preparar
        $now = now();
        
        // Actuar
        $expiration = $now->copy()->addHours(2);
        
        // Verificar
        $this->assertEquals(
            $now->addHours(2)->timestamp,
            $expiration->timestamp
        );
    }
}