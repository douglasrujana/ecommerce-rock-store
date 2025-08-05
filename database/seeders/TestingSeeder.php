<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder específico para pruebas E2E
 * Se ejecuta solo en entorno de testing
 */
class TestingSeeder extends Seeder
{
    public function run(): void
    {
        // Solo ejecutar en testing
        if (!app()->environment('testing')) {
            return;
        }

        // Usuario para pruebas de recuperación de contraseña
        User::factory()->create([
            'name' => 'Usuario E2E Test',
            'email' => 'e2e-test@example.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
        ]);

        // Usuario inactivo para pruebas
        User::factory()->create([
            'name' => 'Usuario Inactivo',
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'activo' => 0,
        ]);

        // Admin para pruebas avanzadas
        User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'activo' => 1,
        ]);
    }
}