# 🎸 GUÍA DE EJECUCIÓN - PRUEBAS E2E ECOMMERCE MUSICAL

## 📋 **PREPARACIÓN DEL ENTORNO**

### 1. **Instalar Playwright**
```bash
cd tests/E2E
npm install
npm run install-browsers
```

### 2. **Iniciar el servidor Laravel**
```bash
# Terminal 1: Servidor Laravel
php artisan serve
```

### 3. **Verificar datos de prueba**
```bash
# Verificar que tienes productos en la BD
php artisan tinker
>>> App\Models\Producto::count()
>>> App\Models\Categoria::count()
```

---

## 🚀 **COMANDOS DE EJECUCIÓN**

### **Pruebas Básicas**
```bash
# Ejecutar todas las pruebas
npm test

# Ejecutar con interfaz visual
npm run test:headed

# Modo debug (paso a paso)
npm run test:debug
```

### **Pruebas Específicas**
```bash
# Solo pruebas de catálogo
npm run test:catalogo

# Solo en móvil
npm run test:mobile

# Prueba específica
npx playwright test tests/01-catalogo.spec.js -g "UC-001.1"
```

### **Reportes**
```bash
# Ver reporte HTML
npm run report

# Generar reporte JSON
npx playwright test --reporter=json
```

---

## 📊 **ESTRUCTURA DE RESULTADOS**

```
tests/E2E/
├── 📁 reports/
│   ├── html/           # Reporte visual
│   ├── results.json    # Datos estructurados
│   └── screenshots/    # Capturas de fallos
├── 📁 test-results/
│   ├── videos/         # Videos de ejecución
│   └── traces/         # Trazas para debugging
```

---

## 🎯 **CASOS DE USO IMPLEMENTADOS**

### ✅ **UC-001: Exploración del Catálogo**
- [x] UC-001.1: Cargar catálogo principal
- [x] UC-001.2: Mostrar información de productos  
- [x] UC-001.3: Mostrar badges de categorización
- [x] UC-001.4: Navegación a ficha de producto
- [x] UC-001.5: Filtro por categoría
- [x] UC-001.6: Filtro por década
- [x] UC-001.7: Combinación de filtros
- [x] UC-001.8: Limpiar filtros
- [x] UC-001.9: Navegación entre páginas

### ✅ **UC-003: Búsqueda Inteligente**
- [x] UC-003.1: Búsqueda por texto libre
- [x] UC-003.2: Búsqueda sin resultados

---

## 🔧 **DEBUGGING Y TROUBLESHOOTING**

### **Problemas Comunes**

1. **Error: "baseURL not reachable"**
   ```bash
   # Verificar que Laravel está corriendo
   curl http://localhost:8000
   ```

2. **Fallos de timeout**
   ```bash
   # Aumentar timeout en playwright.config.js
   timeout: 60000
   ```

3. **Elementos no encontrados**
   ```bash
   # Ejecutar en modo debug
   npm run test:debug
   ```

### **Comandos de Diagnóstico**
```bash
# Ver qué navegadores están instalados
npx playwright --version

# Limpiar cache de Playwright
npx playwright install --force

# Ejecutar una sola prueba con logs
npx playwright test tests/01-catalogo.spec.js --headed --debug
```

---

## 📚 **PRÓXIMOS CAPÍTULOS DE LA GUÍA**

- [ ] **Capítulo 4**: Pruebas de Ficha de Museo
- [ ] **Capítulo 5**: Pruebas de Carrito de Compras  
- [ ] **Capítulo 6**: Pruebas de Responsive Design
- [ ] **Capítulo 7**: Integración con CI/CD
- [ ] **Capítulo 8**: Métricas y Reportes Avanzados

---

## 🎸 **VALOR EDUCATIVO**

Esta guía te enseña:
- ✅ **Playwright avanzado** con Page Object Model
- ✅ **Estrategias de testing** para ecommerce
- ✅ **Casos de uso reales** de aplicaciones musicales
- ✅ **Debugging sistemático** de pruebas E2E
- ✅ **Reportes profesionales** para stakeholders

**¡Tu referencia completa para testing E2E!** 🚀