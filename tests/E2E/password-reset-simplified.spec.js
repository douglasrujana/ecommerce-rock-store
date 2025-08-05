/**
 * 🎭 PRUEBA E2E SIMPLIFICADA - RECUPERACIÓN DE CONTRASEÑA
 * 
 * Versión simplificada que funciona sin APIs de testing complejas
 */

const { test, expect } = require('@playwright/test');

test.describe('🔐 Recuperación de Contraseña - E2E Simplificado', () => {
  
  test('🎯 Flujo básico de recuperación de contraseña', async ({ page }) => {
    // 📍 FASE 1: Acceso al formulario de recuperación
    await page.goto('/password/reset');
    
    // Verificar que estamos en la página correcta
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
    
    // 📍 FASE 2: Probar validación de email
    await page.fill('input[name="email"]', 'email-invalido');
    await page.click('button[type="submit"]');
    
    // Verificar error de validación (ser más específico)
    await expect(page.locator('.invalid-feedback')).toBeVisible();
  });

  test('🚫 Validaciones de formulario', async ({ page }) => {
    await page.goto('/password/reset');

    // Email vacío
    await page.click('button[type="submit"]');
    await expect(page.locator('.invalid-feedback').first()).toBeVisible();

    // Email con formato incorrecto
    await page.fill('input[name="email"]', 'not-an-email');
    await page.click('button[type="submit"]');
    await expect(page.locator('.invalid-feedback').first()).toBeVisible();
  });

  test('⚡ Elementos de UI están presentes', async ({ page }) => {
    await page.goto('/password/reset');

    // Verificar elementos principales
    await expect(page.locator('h1, h2, h3')).toBeVisible();
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
    await expect(page.locator('label')).toBeVisible();
  });

  test('📱 Responsive design básico', async ({ page }) => {
    // Móvil
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/password/reset');
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
    
    // Desktop
    await page.setViewportSize({ width: 1920, height: 1080 });
    await page.goto('/password/reset');
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('♿ Accesibilidad básica', async ({ page }) => {
    await page.goto('/password/reset');

    const emailInput = page.locator('input[name="email"]');
    
    // Verificar atributos de accesibilidad
    await expect(emailInput).toHaveAttribute('type', 'email');
    await expect(emailInput).toHaveAttribute('name', 'email');
    
    // Verificar navegación con teclado
    await emailInput.focus();
    await expect(emailInput).toBeFocused();
    
    await page.keyboard.press('Tab');
    await expect(page.locator('button[type="submit"]')).toBeFocused();
  });

  test('🔗 Navegación desde login', async ({ page }) => {
    await page.goto('/login');
    
    // Buscar enlace de recuperación de contraseña
    const resetLink = page.locator('a[href*="password"], a:has-text("Recuperar"), a:has-text("Olvidé")');
    
    if (await resetLink.count() > 0) {
      await resetLink.first().click();
      await expect(page).toHaveURL(/password/);
      await expect(page.locator('input[name="email"]')).toBeVisible();
    }
  });
});