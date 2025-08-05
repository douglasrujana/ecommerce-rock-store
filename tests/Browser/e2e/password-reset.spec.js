const { test, expect } = require('@playwright/test');
const { faker } = require('@faker-js/faker');

test.describe('Password Reset', () => {
  const testEmail = 'test@example.com';
  const newPassword = 'newPassword123!';

  test.beforeEach(async ({ page }) => {
    // Asegurarse de que el usuario existe en la base de datos
    // Esto normalmente se haría con una API o comando de Laravel
    // Para pruebas reales, deberías usar una base de datos de prueba
  });

  test('user can request password reset', async ({ page }) => {
    // Visitar la página de solicitud de restablecimiento de contraseña
    await page.goto('/password/reset');
    
    // Verificar que estamos en la página correcta
    await expect(page).toHaveTitle(/Recuperar password/);
    
    // Llenar el formulario
    await page.fill('input[name="email"]', testEmail);
    
    // Enviar el formulario
    await page.click('button[type="submit"]');
    
    // Verificar que se muestra el mensaje de éxito
    await expect(page.locator('.alert-success')).toBeVisible();
    await expect(page.locator('.alert-success')).toContainText('Te hemos enviado un enlace');
  });

  test('user cannot request password reset with invalid email', async ({ page }) => {
    // Visitar la página de solicitud de restablecimiento de contraseña
    await page.goto('/password/reset');
    
    // Llenar el formulario con un correo inválido
    await page.fill('input[name="email"]', 'invalid-email@example.com');
    
    // Enviar el formulario
    await page.click('button[type="submit"]');
    
    // Verificar que se muestra un mensaje de error
    await expect(page.locator('.invalid-feedback, .alert-danger')).toBeVisible();
  });

  test('user can reset password with valid token', async ({ page }) => {
    // Nota: En una prueba real, necesitarías:
    // 1. Solicitar un restablecimiento de contraseña
    // 2. Obtener el token de la base de datos
    // 3. Visitar la URL de restablecimiento con ese token
    
    // Para esta prueba, simularemos que ya tenemos un token válido
    // En una implementación real, deberías generar este token y guardarlo en la base de datos
    const token = 'valid-token-12345';
    
    // Visitar la página de restablecimiento de contraseña con el token
    await page.goto(`/password/reset/${token}`);
    
    // Llenar el formulario
    await page.fill('input[name="email"]', testEmail);
    await page.fill('input[name="password"]', newPassword);
    await page.fill('input[name="password_confirmation"]', newPassword);
    
    // Enviar el formulario
    await page.click('button[type="submit"]');
    
    // Verificar que somos redirigidos a la página de inicio de sesión
    await expect(page).toHaveURL(/login/);
    
    // Verificar que se muestra un mensaje de éxito
    await expect(page.locator('.alert-success, .alert-info')).toBeVisible();
    await expect(page.locator('.alert-success, .alert-info')).toContainText(/contraseña ha sido restablecida/);
    
    // Opcional: Probar el inicio de sesión con la nueva contraseña
    await page.fill('input[name="email"]', testEmail);
    await page.fill('input[name="password"]', newPassword);
    await page.click('button[type="submit"]');
    
    // Verificar que el inicio de sesión fue exitoso
    await expect(page).toHaveURL(/dashboard/);
  });
});