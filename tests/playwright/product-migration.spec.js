import { test, expect } from '@playwright/test';

test.describe('Product Page Migration Test', () => {
  const productUrl = 'http://localhost:8000/producto/1';

  test('Hero section elements are present', async ({ page }) => {
    await page.goto(productUrl);

    // Verificar elementos básicos del hero
    await expect(page.locator('h1')).toBeVisible();
    await expect(page.locator('img')).toBeVisible();
    
    // Verificar precio
    await expect(page.locator('text=/\\$[0-9,]+\\.\\d{2}/')).toBeVisible();
    
    // Verificar confidence indicators
    await expect(page.locator('text=Garantía incluida')).toBeVisible();
    await expect(page.locator('text=Envío gratuito')).toBeVisible();
    await expect(page.locator('text=Devolución fácil')).toBeVisible();
  });

  test('Purchase controls work', async ({ page }) => {
    await page.goto(productUrl);

    // Verificar formulario
    await expect(page.locator('#addToCartForm')).toBeVisible();
    await expect(page.locator('#cantidad')).toBeVisible();
    await expect(page.locator('#addToCartBtn')).toBeVisible();
    
    // Test quantity controls
    const quantityInput = page.locator('#cantidad');
    await expect(quantityInput).toHaveValue('1');
    
    await page.locator('[data-action="increment"]').click();
    await expect(quantityInput).toHaveValue('2');
    
    await page.locator('[data-action="decrement"]').click();
    await expect(quantityInput).toHaveValue('1');
  });

  test('All sections present', async ({ page }) => {
    await page.goto(productUrl);

    // Verificar secciones
    await expect(page.locator('text=Ficha del Museo')).toBeVisible();
    await expect(page.locator('text=Canciones y Acordes')).toBeVisible();
    await expect(page.locator('text=Álbumes Relacionados')).toBeVisible();
    
    // Carrito flotante
    await expect(page.locator('[data-action="toggle-cart"]')).toBeVisible();
    await expect(page.locator('#cartBadge')).toBeVisible();
  });

  test('Responsive works', async ({ page }) => {
    // Desktop
    await page.setViewportSize({ width: 1200, height: 800 });
    await page.goto(productUrl);
    await expect(page.locator('h1')).toBeVisible();
    
    // Mobile
    await page.setViewportSize({ width: 375, height: 667 });
    await page.reload();
    await expect(page.locator('h1')).toBeVisible();
  });

  test('Structure intact', async ({ page }) => {
    await page.goto(productUrl);
    
    const structure = await page.evaluate(() => {
      return {
        hasHeroSection: !!document.querySelector('section'),
        hasProductImage: !!document.querySelector('img'),
        hasProductTitle: !!document.querySelector('h1'),
        hasAddToCartForm: !!document.querySelector('#addToCartForm'),
        hasQuantityControls: !!document.querySelector('#cantidad'),
        hasFloatingCart: !!document.querySelector('[data-action="toggle-cart"]')
      };
    });
    
    Object.values(structure).forEach(value => {
      expect(value).toBe(true);
    });
  });
});