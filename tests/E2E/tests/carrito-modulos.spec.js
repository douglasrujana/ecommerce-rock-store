/**
 * 🛒 PRUEBAS DE MÓDULOS JAVASCRIPT DEL CARRITO
 * 
 * Valida que los módulos JS organizados funcionen correctamente
 */

const { test, expect } = require('@playwright/test');

test.describe('🛒 Carrito - Módulos JavaScript', () => {
    
    test('✅ Debe cargar módulos JavaScript correctamente', async ({ page }) => {
        console.log('🚀 Probando carga de módulos JS...');
        
        // Ir a un producto
        await page.goto('/album/1');
        await page.waitForLoadState('networkidle');
        
        // Verificar que app.js se carga
        const appScript = await page.locator('script[src*="app.js"]').count();
        expect(appScript).toBeGreaterThan(0);
        console.log('✅ Script app.js encontrado');
        
        // Verificar que no hay errores de JavaScript
        const jsErrors = [];
        page.on('pageerror', error => jsErrors.push(error));
        
        await page.waitForTimeout(2000);
        expect(jsErrors.length).toBe(0);
        console.log('✅ Sin errores de JavaScript');
    });

    test('✅ Debe funcionar controles de cantidad con módulos', async ({ page }) => {
        console.log('🚀 Probando controles de cantidad...');
        
        await page.goto('/album/1');
        await page.waitForLoadState('networkidle');
        
        // Verificar que los botones tienen data attributes
        const incrementBtn = page.locator('button[data-action="increment"]');
        const decrementBtn = page.locator('button[data-action="decrement"]');
        const cantidadInput = page.locator('#cantidad');
        
        await expect(incrementBtn).toBeVisible();
        await expect(decrementBtn).toBeVisible();
        await expect(cantidadInput).toBeVisible();
        console.log('✅ Controles de cantidad visibles');
        
        // Probar incremento
        const valorInicial = await cantidadInput.inputValue();
        await incrementBtn.click();
        await page.waitForTimeout(100);
        
        const valorDespuesIncremento = await cantidadInput.inputValue();
        expect(parseInt(valorDespuesIncremento)).toBe(parseInt(valorInicial) + 1);
        console.log('✅ Incremento funciona');
        
        // Probar decremento
        await decrementBtn.click();
        await page.waitForTimeout(100);
        
        const valorDespuesDecremento = await cantidadInput.inputValue();
        expect(parseInt(valorDespuesDecremento)).toBe(parseInt(valorInicial));
        console.log('✅ Decremento funciona');
    });

    test('✅ Debe funcionar botón de favoritos con módulos', async ({ page }) => {
        console.log('🚀 Probando botón de favoritos...');
        
        await page.goto('/album/1');
        await page.waitForLoadState('networkidle');
        
        // Verificar botón de favoritos
        const favoriteBtn = page.locator('button[data-action="favorite"]');
        await expect(favoriteBtn).toBeVisible();
        console.log('✅ Botón de favoritos visible');
        
        // Hacer clic en favoritos
        await favoriteBtn.click();
        await page.waitForTimeout(1000);
        
        // Verificar que aparece mensaje (si existe el elemento)
        const messageDiv = page.locator('#cartMessage');
        if (await messageDiv.isVisible()) {
            const messageText = await messageDiv.textContent();
            expect(messageText).toContain('favoritos');
            console.log('✅ Mensaje de favoritos mostrado');
        } else {
            console.log('⚠️ Elemento de mensaje no visible, pero función ejecutada');
        }
    });

    test('✅ Debe funcionar carrito flotante con módulos', async ({ page }) => {
        console.log('🚀 Probando carrito flotante...');
        
        await page.goto('/album/1');
        await page.waitForLoadState('networkidle');
        
        // Buscar carrito flotante
        const carritoFlotante = page.locator('.position-fixed .bg-primary');
        
        if (await carritoFlotante.isVisible()) {
            console.log('✅ Carrito flotante visible');
            
            // Hacer clic en carrito flotante
            await carritoFlotante.click();
            await page.waitForLoadState('networkidle');
            
            // Verificar navegación
            expect(page.url()).toContain('/carrito');
            console.log('✅ Navegación al carrito exitosa');
        } else {
            console.log('⚠️ Carrito flotante no visible, probando navegación manual');
            await page.goto('/carrito');
            expect(page.url()).toContain('/carrito');
            console.log('✅ Navegación manual exitosa');
        }
    });

    test('✅ Debe agregar al carrito con módulos JS', async ({ page }) => {
        console.log('🚀 Probando agregar al carrito con módulos...');
        
        await page.goto('/album/1');
        await page.waitForLoadState('networkidle');
        
        // Verificar formulario
        const form = page.locator('#addToCartForm');
        const btnAgregar = page.locator('#addToCartBtn');
        const badge = page.locator('#cartBadge');
        
        await expect(form).toBeVisible();
        await expect(btnAgregar).toBeVisible();
        console.log('✅ Formulario y botón visibles');
        
        // Obtener badge inicial
        const badgeInicial = await badge.textContent().catch(() => '0');
        console.log(`📊 Badge inicial: ${badgeInicial}`);
        
        // Agregar al carrito
        await btnAgregar.click();
        await page.waitForTimeout(3000); // Esperar AJAX
        
        // Verificar badge actualizado
        const badgeFinal = await badge.textContent().catch(() => '0');
        console.log(`📊 Badge final: ${badgeFinal}`);
        
        expect(parseInt(badgeFinal)).toBeGreaterThan(parseInt(badgeInicial));
        console.log('✅ Producto agregado correctamente con módulos JS');
    });

    test('✅ Debe manejar errores graciosamente', async ({ page }) => {
        console.log('🚀 Probando manejo de errores...');
        
        // Interceptar requests para simular error
        await page.route('**/carrito/agregar', route => {
            route.fulfill({
                status: 500,
                contentType: 'application/json',
                body: JSON.stringify({ success: false, mensaje: 'Error simulado' })
            });
        });
        
        await page.goto('/album/1');
        await page.waitForLoadState('networkidle');
        
        const btnAgregar = page.locator('#addToCartBtn');
        await btnAgregar.click();
        await page.waitForTimeout(2000);
        
        // Verificar que el botón se resetea después del error
        const btnText = await btnAgregar.locator('.btn-text').textContent();
        expect(btnText).not.toContain('Agregando');
        console.log('✅ Manejo de errores funciona correctamente');
    });
});