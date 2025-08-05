// 🎸 PAGE OBJECT MODEL - CATÁLOGO MUSICAL
// Guía metodológica: Capítulo 3.2 - Page Object Model

export class CatalogoPage {
  constructor(page) {
    this.page = page;
    
    // 🔍 SELECTORES DEL CATÁLOGO
    this.searchInput = 'input[name="search"]';
    this.searchButton = 'button:has-text("Buscar")';
    this.categoriaSelect = 'select[name="categoria"]';
    this.decadaSelect = 'select[name="decada"]';
    this.paisSelect = 'select[name="pais"]';
    this.sortSelect = 'select[name="sort"]';
    this.limpiarFiltros = 'a:has-text("Limpiar")';
    
    // 📀 PRODUCTOS
    this.productCards = '.card.h-100';
    this.productTitle = '.fw-bolder';
    this.productPrice = '.text-primary';
    this.verAlbumButton = '.btn.btn-outline-dark:has-text("Ver álbum")';
    this.productBadges = '.badge';
    
    // 📄 PAGINACIÓN
    this.paginationLinks = '.pagination a';
    this.nextPage = 'a[rel="next"]';
    this.prevPage = 'a[rel="prev"]';
    
    // 📊 INFORMACIÓN
    this.resultadosInfo = '.text-muted.small';
    this.noResultados = 'h4:has-text("No se encontraron productos")';
  }

  // 🌐 NAVEGACIÓN
  async goto() {
    await this.page.goto('/');
    await this.page.waitForLoadState('networkidle');
  }

  // 🔍 BÚSQUEDA
  async buscar(termino) {
    await this.page.fill(this.searchInput, termino);
    await this.page.click(this.searchButton);
    await this.page.waitForLoadState('networkidle');
  }

  // 🎸 FILTROS
  async filtrarPorCategoria(categoria) {
    await this.page.selectOption(this.categoriaSelect, { label: categoria });
    // El select tiene onchange="this.form.submit()" así que esperar navegación
    await this.page.waitForLoadState('networkidle');
  }

  async filtrarPorDecada(decada) {
    await this.page.selectOption(this.decadaSelect, { label: decada });
    // El select tiene onchange="this.form.submit()" así que esperar navegación  
    await this.page.waitForLoadState('networkidle');
  }

  async filtrarPorPais(pais) {
    await this.page.selectOption(this.paisSelect, { label: pais });
    await this.page.waitForLoadState('networkidle');
  }

  async ordenarPor(criterio) {
    await this.page.selectOption(this.sortSelect, criterio);
    await this.page.waitForLoadState('networkidle');
  }

  async limpiarTodosFiltros() {
    await this.page.click(this.limpiarFiltros);
    await this.page.waitForLoadState('networkidle');
  }

  // 📀 PRODUCTOS
  async obtenerProductos() {
    return await this.page.locator(this.productCards).all();
  }

  async obtenerTitulosProductos() {
    return await this.page.locator(this.productTitle).allTextContents();
  }

  async obtenerPreciosProductos() {
    return await this.page.locator(this.productPrice).allTextContents();
  }

  async clickPrimerProducto() {
    // Esperar a que los productos carguen
    await this.page.waitForSelector(this.productCards);
    await this.page.locator(this.verAlbumButton).first().click();
    await this.page.waitForLoadState('networkidle');
  }

  // 📊 VALIDACIONES
  async tieneResultados() {
    const productos = await this.obtenerProductos();
    return productos.length > 0;
  }

  async noTieneResultados() {
    return await this.page.locator(this.noResultados).isVisible();
  }

  async obtenerNumeroResultados() {
    const info = await this.page.locator(this.resultadosInfo).textContent();
    const match = info.match(/de (\d+) productos/);
    return match ? parseInt(match[1]) : 0;
  }

  // 📄 PAGINACIÓN
  async irSiguientePagina() {
    if (await this.page.locator(this.nextPage).isVisible()) {
      await this.page.click(this.nextPage);
      await this.page.waitForLoadState('networkidle');
      return true;
    }
    return false;
  }

  async irPaginaAnterior() {
    if (await this.page.locator(this.prevPage).isVisible()) {
      await this.page.click(this.prevPage);
      await this.page.waitForLoadState('networkidle');
      return true;
    }
    return false;
  }
}