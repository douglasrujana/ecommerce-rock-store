/**
 * 🎭 PRUEBA E2E CON SEEDERS - MÁS ELEGANTE
 * 
 * Usa seeders de Laravel en lugar de APIs de testing
 * Más profesional y mantenible
 */

const { test, expect } = require('@playwright/test');
const { exec } = require('child_process');
const { promisify } = require('util');

const execAsync = promisify(exec);

test.describe('🔐 Recuperación con Seeders', () => {
  
  // Preparar datos antes de todas las pruebas
  test.beforeAll(async () => {
    // Ejecutar migraciones y seeders
    await execAsync('php artisan migrate:fresh --seed --seeder=TestingSeeder --env=testing');
  });

  test('🎯 Flujo con usuario real del seeder', async ({ page }) => {
    // Usuario creado por el seeder
    const testUser = {
      email: 'e2e-test@example.com',
      password: 'password123',
      newPassword: 'newPassword456!'
    };

    // 1. Solicitar recuperación con usuario real
    await page.goto('/password/reset');
    await page.fill('input[name="email"]', testUser.email);
    await page.click('button[type="submit"]');
    
    // Verificar mensaje de éxito
    await expect(page.locator('.alert-success, .alert-info')).toContainText(/enlace/i);

    // 2. Simular obtención de token (en prueba real consultarías la BD)
    // Por ahora, solo verificamos que el flujo inicial funciona
    await expect(page).toHaveURL(/password\/reset/);
  });

  test('🚫 Usuario inactivo no puede recuperar', async ({ page }) => {
    await page.goto('/password/reset');
    await page.fill('input[name="email"]', 'inactive@example.com');
    await page.click('button[type="submit"]');
    
    // Debería funcionar igual (por seguridad no revelamos si existe)
    await expect(page.locator('.alert-success, .alert-info')).toBeVisible();
  });

  test('❌ Email inexistente muestra error', async ({ page }) => {
    await page.goto('/password/reset');
    await page.fill('input[name="email"]', 'noexiste@example.com');
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-danger')).toBeVisible();
  });

  // Limpiar después de todas las pruebas
  test.afterAll(async () => {
    // Limpiar base de datos de testing
    await execAsync('php artisan migrate:fresh --env=testing');
  });
});