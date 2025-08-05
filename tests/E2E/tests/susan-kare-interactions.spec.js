/**
 * 🎨 SUSAN KARE INTERACTIONS E2E TESTS
 * Validar que los iconos y micro-interacciones funcionen correctamente
 */

import { test, expect } from '@playwright/test';

test.describe('Susan Kare Iconography', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto('/album/1');
        await page.waitForLoadState('networkidle');
    });

    test('should display Susan Kare icons correctly', async ({ page }) => {
        // Verificar que los iconos Susan Kare están presentes
        await expect(page.locator('.kare-icon-cart')).toBeVisible();
        await expect(page.locator('.kare-icon-heart')).toBeVisible();
        await expect(page.locator('.kare-icon-album')).toBeVisible();
        await expect(page.locator('.kare-icon-note')).toBeVisible();
        await expect(page.locator('.kare-icon-info')).toBeVisible();
    });

    test('should animate heart icon when clicked', async ({ page }) => {
        const heartIcon = page.locator('.kare-icon-heart').first();
        
        // Click en favorito
        await page.click('[data-action="favorite"]');
        
        // Verificar que se activa
        await expect(heartIcon).toHaveClass(/active/);
        
        // Click nuevamente para desactivar
        await page.click('[data-action="favorite"]');
        await expect(heartIcon).not.toHaveClass(/active/);
    });

    test('should show loading state when adding to cart', async ({ page }) => {
        const addToCartBtn = page.locator('#addToCartBtn');
        
        // Click en agregar al carrito
        await addToCartBtn.click();
        
        // Verificar estado de carga
        await expect(addToCartBtn).toContainText('Agregando...');
        
        // Esperar estado de éxito
        await expect(addToCartBtn).toContainText('¡Agregado!', { timeout: 3000 });
    });

    test('should show tooltip on info icon hover', async ({ page }) => {
        const infoIcon = page.locator('.kare-icon-info').first();
        
        // Hover sobre el icono de información
        await infoIcon.hover();
        
        // Verificar que aparece el tooltip
        await expect(page.locator('.kare-tooltip')).toBeVisible();
    });
});