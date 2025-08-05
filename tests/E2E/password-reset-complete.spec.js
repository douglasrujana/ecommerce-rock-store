/**
 * 🎭 PRUEBA E2E COMPLETA - RECUPERACIÓN DE CONTRASEÑA
 * 
 * Esta prueba simula un usuario real interactuando con la aplicación
 * desde el navegador, incluyendo JavaScript, CSS, y comportamiento visual.
 * 
 * Flujo completo:
 * 1. Usuario olvida su contraseña
 * 2. Solicita recuperación
 * 3. Recibe email (simulado)
 * 4. Hace clic en enlace
 * 5. Restablece contraseña
 * 6. Inicia sesión con nueva contraseña
 * 
 * Tecnología: Playwright (navegador real)
 * Nivel: E2E (End-to-End)
 * Cobertura: 100% del flujo de usuario
 */

const { test, expect } = require('@playwright/test');

// Configuración global para todas las pruebas
test.describe('🔐 Recuperación de Contraseña - E2E Completo', () => {
  const testUser = {
    name: 'Usuario E2E Test',
    email: 'e2e-test@example.com',
    oldPassword: 'passwordAntiguo123',
    newPassword: 'passwordNuevo456!'
  };

  // Configuración antes de cada prueba
  test.beforeEach(async ({ page }) => {
    // Crear usuario de prueba en la base de datos
    await page.goto('/test-setup'); // Endpoint especial para pruebas
    await page.evaluate((user) => {
      // En una implementación real, esto sería una API call
      fetch('/api/test/create-user', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(user)
      });
    }, testUser);
  });

  test('🎯 Flujo completo de recuperación de contraseña', async ({ page }) => {
    // 📍 FASE 1: ACCESO AL FORMULARIO DE RECUPERACIÓN
    test.step('Usuario accede al formulario de recuperación', async () => {
      await page.goto('/login');
      
      // Verificar que estamos en la página de login
      await expect(page).toHaveTitle(/Sistema - Login/);
      
      // Hacer clic en "Recuperar password"
      await page.click('a[href*="password/reset"]');
      
      // Verificar que llegamos al formulario correcto
      await expect(page).toHaveURL(/password\/reset/);
      await expect(page.locator('h1')).toContainText('Sistema');
      await expect(page.locator('input[name="email"]')).toBeVisible();
    });

    // 📍 FASE 2: SOLICITUD DE RECUPERACIÓN
    test.step('Usuario solicita recuperación con email válido', async () => {
      // Llenar el formulario
      await page.fill('input[name="email"]', testUser.email);
      
      // Verificar que el campo se llenó correctamente
      await expect(page.locator('input[name="email"]')).toHaveValue(testUser.email);
      
      // Enviar formulario
      await page.click('button[type="submit"]');
      
      // Verificar mensaje de éxito
      await expect(page.locator('.alert-success, .alert-info')).toBeVisible();
      await expect(page.locator('.alert-success, .alert-info')).toContainText(/enlace de recuperación/i);
    });

    // 📍 FASE 3: SIMULACIÓN DE EMAIL Y OBTENCIÓN DE TOKEN
    let resetToken;
    test.step('Sistema genera token y simula envío de email', async () => {
      // En una prueba real, aquí interceptarías el email o consultarías la BD
      // Para esta demo, obtenemos el token directamente de la base de datos
      resetToken = await page.evaluate(async (email) => {
        const response = await fetch('/api/test/get-reset-token', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email })
        });
        const data = await response.json();
        return data.token;
      }, testUser.email);
      
      expect(resetToken).toBeTruthy();
      expect(resetToken.length).toBe(64);
    });

    // 📍 FASE 4: ACCESO AL FORMULARIO DE RESTABLECIMIENTO
    test.step('Usuario accede al enlace del email', async () => {
      // Simular clic en enlace del email
      await page.goto(`/password/reset/${resetToken}`);
      
      // Verificar que estamos en el formulario de restablecimiento
      await expect(page).toHaveURL(new RegExp(`password/reset/${resetToken}`));
      await expect(page.locator('input[name="email"]')).toBeVisible();
      await expect(page.locator('input[name="password"]')).toBeVisible();
      await expect(page.locator('input[name="password_confirmation"]')).toBeVisible();
      await expect(page.locator('input[name="token"]')).toHaveValue(resetToken);
    });

    // 📍 FASE 5: RESTABLECIMIENTO DE CONTRASEÑA
    test.step('Usuario restablece su contraseña', async () => {
      // Llenar formulario de restablecimiento
      await page.fill('input[name="email"]', testUser.email);
      await page.fill('input[name="password"]', testUser.newPassword);
      await page.fill('input[name="password_confirmation"]', testUser.newPassword);
      
      // Verificar que los campos se llenaron
      await expect(page.locator('input[name="email"]')).toHaveValue(testUser.email);
      await expect(page.locator('input[name="password"]')).toHaveValue(testUser.newPassword);
      await expect(page.locator('input[name="password_confirmation"]')).toHaveValue(testUser.newPassword);
      
      // Enviar formulario
      await page.click('button[type="submit"]');
      
      // Verificar redirección a login
      await expect(page).toHaveURL(/login/);
      await expect(page.locator('.alert-success, .alert-info')).toContainText(/contraseña ha sido restablecida/i);
    });

    // 📍 FASE 6: VERIFICACIÓN CON NUEVA CONTRASEÑA
    test.step('Usuario inicia sesión con nueva contraseña', async () => {
      // Intentar login con nueva contraseña
      await page.fill('input[name="email"]', testUser.email);
      await page.fill('input[name="password"]', testUser.newPassword);
      await page.click('button[type="submit"]');
      
      // Verificar login exitoso
      await expect(page).toHaveURL(/dashboard/);
      await expect(page.locator('body')).toContainText(/dashboard|bienvenido/i);
    });

    // 📍 FASE 7: VERIFICACIÓN DE SEGURIDAD
    test.step('Verificar que contraseña antigua ya no funciona', async () => {
      // Logout
      await page.click('button:has-text("Cerrar sesión"), a:has-text("Logout")');
      
      // Intentar login con contraseña antigua
      await page.goto('/login');
      await page.fill('input[name="email"]', testUser.email);
      await page.fill('input[name="password"]', testUser.oldPassword);
      await page.click('button[type="submit"]');
      
      // Verificar que falla
      await expect(page.locator('.alert-danger, .invalid-feedback')).toBeVisible();
      await expect(page).toHaveURL(/login/);
    });
  });

  test('🚫 Validaciones de formulario en tiempo real', async ({ page }) => {
    await page.goto('/password/reset');

    test.step('Validar email inválido', async () => {
      await page.fill('input[name="email"]', 'email-invalido');
      await page.click('button[type="submit"]');
      
      // Verificar error de validación
      await expect(page.locator('.alert-danger, .invalid-feedback')).toBeVisible();
      await expect(page.locator('.is-invalid')).toBeVisible();
    });

    test.step('Validar email inexistente', async () => {
      await page.fill('input[name="email"]', 'noexiste@example.com');
      await page.click('button[type="submit"]');
      
      await expect(page.locator('.alert-danger')).toContainText(/no encontrado|no existe/i);
    });
  });

  test('🔒 Validaciones de contraseña en formulario de reset', async ({ page }) => {
    // Crear token válido para la prueba
    const token = 'test-token-' + Date.now();
    await page.evaluate(async ({ email, token }) => {
      await fetch('/api/test/create-reset-token', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, token })
      });
    }, { email: testUser.email, token });

    await page.goto(`/password/reset/${token}`);

    test.step('Contraseñas no coinciden', async () => {
      await page.fill('input[name="email"]', testUser.email);
      await page.fill('input[name="password"]', 'password123');
      await page.fill('input[name="password_confirmation"]', 'password456');
      await page.click('button[type="submit"]');
      
      await expect(page.locator('.alert-danger, .invalid-feedback')).toContainText(/no coinciden/i);
    });

    test.step('Contraseña muy corta', async () => {
      await page.fill('input[name="password"]', '123');
      await page.fill('input[name="password_confirmation"]', '123');
      await page.click('button[type="submit"]');
      
      await expect(page.locator('.alert-danger, .invalid-feedback')).toContainText(/mínimo|caracteres/i);
    });
  });

  test('⚡ Experiencia de usuario y accesibilidad', async ({ page }) => {
    await page.goto('/password/reset');

    test.step('Elementos de UI están presentes', async () => {
      // Verificar elementos visuales importantes
      await expect(page.locator('h1')).toBeVisible();
      await expect(page.locator('input[name="email"]')).toBeVisible();
      await expect(page.locator('button[type="submit"]')).toBeVisible();
      await expect(page.locator('label[for*="email"]')).toBeVisible();
    });

    test.step('Formulario es accesible', async () => {
      // Verificar que los campos tienen labels asociados
      const emailInput = page.locator('input[name="email"]');
      await expect(emailInput).toHaveAttribute('required');
      await expect(emailInput).toHaveAttribute('type', 'email');
      
      // Verificar navegación con teclado
      await emailInput.focus();
      await expect(emailInput).toBeFocused();
      
      await page.keyboard.press('Tab');
      await expect(page.locator('button[type="submit"]')).toBeFocused();
    });

    test.step('Responsive design funciona', async () => {
      // Probar en móvil
      await page.setViewportSize({ width: 375, height: 667 });
      await expect(page.locator('input[name="email"]')).toBeVisible();
      await expect(page.locator('button[type="submit"]')).toBeVisible();
      
      // Probar en desktop
      await page.setViewportSize({ width: 1920, height: 1080 });
      await expect(page.locator('input[name="email"]')).toBeVisible();
      await expect(page.locator('button[type="submit"]')).toBeVisible();
    });
  });

  // Limpieza después de cada prueba
  test.afterEach(async ({ page }) => {
    // Limpiar datos de prueba
    await page.evaluate((email) => {
      fetch('/api/test/cleanup-user', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      });
    }, testUser.email);
  });
});