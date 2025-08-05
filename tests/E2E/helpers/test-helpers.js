/**
 * 🛠️ HELPERS PARA PRUEBAS E2E
 * 
 * Funciones de utilidad para simplificar las pruebas E2E
 * y mantener el código DRY (Don't Repeat Yourself).
 */

const { expect } = require('@playwright/test');

/**
 * Helper para crear usuarios de prueba
 */
async function createTestUser(page, userData) {
  return await page.evaluate(async (user) => {
    const response = await fetch('/api/test/create-user', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(user)
    });
    return await response.json();
  }, userData);
}

/**
 * Helper para obtener token de reset de la base de datos
 */
async function getResetToken(page, email) {
  return await page.evaluate(async (userEmail) => {
    const response = await fetch('/api/test/get-reset-token', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: userEmail })
    });
    const data = await response.json();
    return data.token;
  }, email);
}

/**
 * Helper para limpiar datos de prueba
 */
async function cleanupTestUser(page, email) {
  return await page.evaluate(async (userEmail) => {
    await fetch('/api/test/cleanup-user', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: userEmail })
    });
  }, email);
}

/**
 * Helper para login de usuario
 */
async function loginUser(page, email, password) {
  await page.goto('/login');
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
}

/**
 * Helper para llenar formulario de recuperación
 */
async function fillPasswordResetForm(page, email) {
  await page.fill('input[name="email"]', email);
  await page.click('button[type="submit"]');
}

/**
 * Helper para llenar formulario de nueva contraseña
 */
async function fillNewPasswordForm(page, email, password, token) {
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.fill('input[name="password_confirmation"]', password);
  
  // Verificar que el token está presente
  await expect(page.locator('input[name="token"]')).toHaveValue(token);
  
  await page.click('button[type="submit"]');
}

/**
 * Helper para verificar mensajes de alerta
 */
async function verifyAlertMessage(page, type, message) {
  const alertSelector = `.alert-${type}, .alert`;
  await expect(page.locator(alertSelector)).toBeVisible();
  if (message) {
    await expect(page.locator(alertSelector)).toContainText(message);
  }
}

/**
 * Helper para verificar errores de validación
 */
async function verifyValidationError(page, message) {
  const errorSelectors = [
    '.alert-danger',
    '.invalid-feedback',
    '.is-invalid',
    '.text-danger'
  ];
  
  let errorFound = false;
  for (const selector of errorSelectors) {
    try {
      await expect(page.locator(selector)).toBeVisible({ timeout: 2000 });
      if (message) {
        await expect(page.locator(selector)).toContainText(message);
      }
      errorFound = true;
      break;
    } catch (e) {
      // Continuar con el siguiente selector
    }
  }
  
  if (!errorFound) {
    throw new Error('No se encontró ningún mensaje de error de validación');
  }
}

module.exports = {
  createTestUser,
  getResetToken,
  cleanupTestUser,
  loginUser,
  fillPasswordResetForm,
  fillNewPasswordForm,
  verifyAlertMessage,
  verifyValidationError
};