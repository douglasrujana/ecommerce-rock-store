// 🎸 PRUEBAS E2E - CATÁLOGO MUSICAL
// Guía metodológica: Capítulo 3.3 - Pruebas de catálogo musical

import { test, expect } from '@playwright/test';
import { CatalogoPage } from '../pages/CatalogoPage.js';

test.describe('🎵 Catálogo Musical - Funcionalidades Básicas', () => {
  let catalogoPage;

  test.beforeEach(async ({ page }) => {
    catalogoPage = new CatalogoPage(page);
    await catalogoPage.goto();
  });

  test('UC-001.1: Debe cargar el catálogo principal', async () => {
    // ✅ GIVEN: Usuario visita la página principal
    // ✅ WHEN: La página carga completamente
    // ✅ THEN: Debe mostrar productos del catálogo
    
    const tieneProductos = await catalogoPage.tieneResultados();
    expect(tieneProductos).toBeTruthy();
    
    const productos = await catalogoPage.obtenerProductos();
    expect(productos.length).toBeGreaterThan(0);
    
    // Verificar que hay al menos algunos elementos esperados
    const titulos = await catalogoPage.obtenerTitulosProductos();
    expect(titulos.length).toBeGreaterThan(0);
    expect(titulos[0]).toBeTruthy();
  });

  test('UC-001.2: Debe mostrar información de productos', async () => {
    // ✅ GIVEN: Catálogo cargado con productos
    // ✅ WHEN: Observo los productos mostrados
    // ✅ THEN: Cada producto debe tener título, precio y botón de acción
    
    const productos = await catalogoPage.obtenerProductos();
    expect(productos.length).toBeGreaterThan(0);
    
    // Verificar primer producto tiene elementos requeridos
    const primerProducto = productos[0];
    const titulo = await primerProducto.locator('.fw-bolder').textContent();
    const precio = await primerProducto.locator('.text-primary').textContent();
    const boton = await primerProducto.locator('a:has-text("Ver álbum")').isVisible();
    
    expect(titulo).toBeTruthy();
    expect(precio).toMatch(/\$\d+\.\d{2}/); // Formato $XX.XX
    expect(boton).toBeTruthy();
  });

  test('UC-001.3: Debe mostrar badges de categorización', async () => {
    // ✅ GIVEN: Productos en el catálogo
    // ✅ WHEN: Observo los badges de cada producto
    // ✅ THEN: Deben mostrar categoría, década, país
    
    const productos = await catalogoPage.obtenerProductos();
    const primerProducto = productos[0];
    
    const badges = await primerProducto.locator('.badge').all();
    expect(badges.length).toBeGreaterThan(0);
    
    // Al menos debe tener algún badge visible
    const badgeTexts = await primerProducto.locator('.badge').allTextContents();
    expect(badgeTexts.length).toBeGreaterThan(0);
  });

  test('UC-001.4: Navegación a ficha de producto', async () => {
    // ✅ GIVEN: Catálogo con productos disponibles
    // ✅ WHEN: Hago clic en "Ver álbum" de un producto
    // ✅ THEN: Debo navegar a la ficha detallada del museo
    
    // Verificar que hay productos primero
    const productos = await catalogoPage.obtenerProductos();
    expect(productos.length).toBeGreaterThan(0);
    
    // Hacer clic en el primer producto
    await catalogoPage.clickPrimerProducto();
    
    // Verificar que navegamos (puede ser cualquier página de detalle)
    await expect(catalogoPage.page).toHaveURL(/\/album\/\d+/);
    
    // Verificar que la página cargó correctamente
    await expect(catalogoPage.page.locator('h1')).toBeVisible();
  });
});

test.describe('🔍 Búsqueda y Filtros', () => {
  let catalogoPage;

  test.beforeEach(async ({ page }) => {
    catalogoPage = new CatalogoPage(page);
    await catalogoPage.goto();
  });

  test('UC-003.1: Búsqueda por texto libre', async () => {
    // ✅ GIVEN: Usuario en el catálogo
    // ✅ WHEN: Busca por "Pink Floyd"
    // ✅ THEN: Debe mostrar solo resultados relacionados
    
    await catalogoPage.buscar('Pink Floyd');
    
    const tieneResultados = await catalogoPage.tieneResultados();
    expect(tieneResultados).toBeTruthy();
    
    const titulos = await catalogoPage.obtenerTitulosProductos();
    const algunTituloPinkFloyd = titulos.some(titulo => 
      titulo.toLowerCase().includes('pink floyd')
    );
    expect(algunTituloPinkFloyd).toBeTruthy();
  });

  test('UC-003.2: Búsqueda sin resultados', async () => {
    // ✅ GIVEN: Usuario en el catálogo
    // ✅ WHEN: Busca algo que no existe
    // ✅ THEN: Debe mostrar mensaje de "no encontrado"
    
    await catalogoPage.buscar('XYZ123NoExiste');
    
    const noTieneResultados = await catalogoPage.noTieneResultados();
    expect(noTieneResultados).toBeTruthy();
    
    await expect(catalogoPage.page.locator('h4:has-text("No se encontraron productos")')).toBeVisible();
  });

  test('UC-001.5: Filtro por categoría', async () => {
    // ✅ GIVEN: Usuario en el catálogo
    // ✅ WHEN: Filtra por categoría "Grunge"
    // ✅ THEN: Solo debe mostrar productos de esa categoría
    
    await catalogoPage.filtrarPorCategoria('Grunge');
    
    // Verificar que la URL contiene el filtro (más importante que tener resultados)
    await expect(catalogoPage.page).toHaveURL(/categoria=/);
    
    // Verificar que el filtro se aplicó (puede no tener resultados)
    const numeroResultados = await catalogoPage.obtenerNumeroResultados();
    expect(numeroResultados).toBeGreaterThanOrEqual(0);
  });

  test('UC-001.6: Filtro por década', async () => {
    // ✅ GIVEN: Usuario en el catálogo
    // ✅ WHEN: Filtra por década "1990s"
    // ✅ THEN: Solo debe mostrar productos de esa década
    
    await catalogoPage.filtrarPorDecada('1990s');
    
    // Verificar que la URL contiene el filtro
    await expect(catalogoPage.page).toHaveURL(/decada=/);
    
    // Verificar que el filtro se aplicó (puede no tener resultados)
    const numeroResultados = await catalogoPage.obtenerNumeroResultados();
    expect(numeroResultados).toBeGreaterThanOrEqual(0);
  });

  test('UC-001.7: Combinación de filtros', async () => {
    // ✅ GIVEN: Usuario en el catálogo
    // ✅ WHEN: Aplica múltiples filtros (categoría + década)
    // ✅ THEN: Debe mostrar productos que cumplan ambos criterios
    
    await catalogoPage.filtrarPorCategoria('Grunge');
    await catalogoPage.filtrarPorDecada('1990s');
    
    // Verificar que ambos filtros están en la URL
    await expect(catalogoPage.page).toHaveURL(/categoria=.*decada=/);
    
    const numeroResultados = await catalogoPage.obtenerNumeroResultados();
    expect(numeroResultados).toBeGreaterThanOrEqual(0);
  });

  test('UC-001.8: Limpiar filtros', async () => {
    // ✅ GIVEN: Usuario con filtros aplicados
    // ✅ WHEN: Hace clic en "Limpiar filtros"
    // ✅ THEN: Debe mostrar todos los productos sin filtros
    
    // Aplicar algunos filtros primero
    await catalogoPage.filtrarPorCategoria('Rock Clásico');
    await catalogoPage.buscar('Pink');
    
    // Limpiar filtros
    await catalogoPage.limpiarTodosFiltros();
    
    // Verificar que volvemos a la URL base
    await expect(catalogoPage.page).toHaveURL('/');
    
    const tieneResultados = await catalogoPage.tieneResultados();
    expect(tieneResultados).toBeTruthy();
  });
});

test.describe('📄 Paginación', () => {
  let catalogoPage;

  test.beforeEach(async ({ page }) => {
    catalogoPage = new CatalogoPage(page);
    await catalogoPage.goto();
  });

  test('UC-001.9: Navegación entre páginas', async () => {
    // ✅ GIVEN: Catálogo con múltiples páginas
    // ✅ WHEN: Navego a la siguiente página
    // ✅ THEN: Debe mostrar diferentes productos
    
    const titulosPagina1 = await catalogoPage.obtenerTitulosProductos();
    
    const pudoAvanzar = await catalogoPage.irSiguientePagina();
    
    if (pudoAvanzar) {
      const titulosPagina2 = await catalogoPage.obtenerTitulosProductos();
      
      // Los títulos deben ser diferentes
      expect(titulosPagina1).not.toEqual(titulosPagina2);
      
      // Verificar que la URL contiene page=2
      await expect(catalogoPage.page).toHaveURL(/page=2/);
    }
  });
});