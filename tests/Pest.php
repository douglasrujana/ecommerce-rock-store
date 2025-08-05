<?php

/**
 * Configuración global de Pest para el proyecto Laravel
 * 
 * Este archivo define la configuración base para todas las pruebas Pest,
 * incluyendo traits, helpers y configuraciones globales.
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case Base
|--------------------------------------------------------------------------
|
| Todas las pruebas Pest extenderán de TestCase por defecto,
| proporcionando acceso a todas las funcionalidades de Laravel Testing.
|
*/

uses(TestCase::class)->in('Feature');
uses(TestCase::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Traits Globales
|--------------------------------------------------------------------------
|
| RefreshDatabase se aplicará automáticamente a todas las pruebas Feature,
| asegurando un estado limpio de base de datos para cada prueba.
|
*/

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Funciones Helper Globales
|--------------------------------------------------------------------------
|
| Funciones de utilidad disponibles en todas las pruebas.
|
*/

/**
 * Crear un usuario autenticado para pruebas
 */
function actingAsUser($attributes = [])
{
    $user = \App\Models\User::factory()->create(array_merge([
        'activo' => 1,
    ], $attributes));
    
    return test()->actingAs($user);
}

/**
 * Crear un usuario administrador para pruebas
 */
function actingAsAdmin($attributes = [])
{
    $user = \App\Models\User::factory()->create(array_merge([
        'activo' => 1,
    ], $attributes));
    
    // Asignar rol de administrador si existe
    if (class_exists(\Spatie\Permission\Models\Role::class)) {
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $user->assignRole($adminRole);
    }
    
    return test()->actingAs($user);
}

/*
|--------------------------------------------------------------------------
| Expectativas Personalizadas
|--------------------------------------------------------------------------
|
| Expectativas personalizadas para hacer las pruebas más expresivas.
|
*/

expect()->extend('toBeValidEmail', function () {
    return $this->toMatch('/^[^\s@]+@[^\s@]+\.[^\s@]+$/');
});

expect()->extend('toHaveValidationError', function ($field) {
    return $this->toHaveKey("errors.{$field}");
});

/*
|--------------------------------------------------------------------------
| Configuraciones de Dataset
|--------------------------------------------------------------------------
|
| Datasets reutilizables para pruebas parametrizadas.
|
*/

dataset('invalid_emails', [
    'email vacío' => [''],
    'email sin @' => ['invalid-email'],
    'email sin dominio' => ['test@'],
    'email sin usuario' => ['@domain.com'],
    'email con espacios' => ['test @domain.com'],
]);

dataset('weak_passwords', [
    'muy corta' => ['123'],
    'solo números' => ['12345678'],
    'solo letras' => ['password'],
    'común' => ['password123'],
]);

dataset('strong_passwords', [
    'alfanumérica con símbolos' => ['MyStr0ng!Pass'],
    'larga y compleja' => ['ThisIsAVerySecurePassword123!'],
    'con caracteres especiales' => ['P@ssw0rd#2024'],
]);