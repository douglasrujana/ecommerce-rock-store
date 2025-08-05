# 🛒 GUÍA DE PRUEBAS E2E - MÓDULO CARRITO

## 📋 Descripción General

Esta suite de pruebas E2E valida completamente el módulo de carrito de compras del ecommerce musical Rock Store. Implementa metodología TDD (Test-Driven Development) con casos de prueba exhaustivos que cubren desde funcionalidades básicas hasta flujos complejos de usuario.

## 🎯 Objetivos de las Pruebas

### Funcionalidades Principales Cubiertas:
- ✅ **Agregar productos al carrito** desde página de detalle
- ✅ **Gestionar cantidades** (incrementar, decrementar, establecer)
- ✅ **Eliminar productos** individuales y vaciar carrito completo
- ✅ **Calcular totales** (subtotal, impuestos, total final)
- ✅ **Validar límites** de cantidad (1-99 unidades)
- ✅ **Persistencia de sesión** entre navegaciones
- ✅ **Experiencia responsive** en dispositivos móviles
- ✅ **Manejo de errores** y casos edge

### Flujos de Usuario Validados:
- 🎯 **Comprador Decidido**: Sabe qué quiere, compra directo
- 🔍 **Comprador Explorador**: Compara múltiples productos
- 🤔 **Comprador Indeciso**: Abandona y vuelve múltiples veces
- 📱 **Comprador Móvil**: Experiencia en dispositivos móviles
- 📦 **Comprador Bulk**: Cantidades grandes
- ⚡ **Pruebas de Performance**: Operaciones rápidas y carga

## 📁 Estructura de Archivos

```
tests/E2E/
├── pages/
│   ├── CarritoPage.js          # Page Object Model del carrito
│   └── ProductoPage.js         # Page Object Model de productos
├── fixtures/
│   └── carrito-test-data.json  # Datos de prueba
├── helpers/
│   └── carrito-helpers.js      # Utilidades para pruebas
├── tests/
│   ├── 02-carrito-agregar.spec.js      # Pruebas de agregar productos
│   ├── 03-carrito-gestionar.spec.js    # Pruebas de gestión
│   └── 04-carrito-flujo-completo.spec.js # Flujos completos
├── run-carrito-tests.bat       # Script de ejecución
└── CARRITO-TESTING-GUIDE.md    # Esta guía
```

## 🚀 Ejecución de Pruebas

### Prerrequisitos
1. **Laravel corriendo**: `php artisan serve` en puerto 8000
2. **Node.js instalado**: Versión 16 o superior
3. **Playwright instalado**: `npm install @playwright/test`
4. **Base de datos con productos**: Ejecutar seeders si es necesario

### Métodos de Ejecución

#### 1. Script Automatizado (Recomendado)
```bash
# Ejecutar script interactivo
./run-carrito-tests.bat

# Opciones disponibles:
# 1. Todas las pruebas del carrito
# 2. Solo pruebas de agregar productos
# 3. Solo pruebas de gestión
# 4. Solo flujos completos
# 5. Modo debug con screenshots
# 6. Reporte completo
# 7. Pruebas de performance
# 8. Pruebas móviles
# 9. Prueba específica
```

#### 2. Comandos Directos
```bash
# Todas las pruebas del carrito
npx playwright test tests/02-carrito-agregar.spec.js tests/03-carrito-gestionar.spec.js tests/04-carrito-flujo-completo.spec.js

# Pruebas específicas
npx playwright test tests/02-carrito-agregar.spec.js
npx playwright test tests/03-carrito-gestionar.spec.js
npx playwright test tests/04-carrito-flujo-completo.spec.js

# Con reporte HTML
npx playwright test --reporter=html

# Modo debug
npx playwright test --debug --headed

# Pruebas móviles
npx playwright test --project=mobile

# Prueba específica por nombre
npx playwright test --grep "Debe agregar producto con cantidad por defecto"
```

## 📊 Casos de Prueba Detallados

### 🛒 02-carrito-agregar.spec.js

#### ✅ Agregar producto con cantidad por defecto
- **Objetivo**: Verificar agregado básico con cantidad 1
- **Pasos**: Navegar → Agregar → Verificar confirmación → Validar carrito
- **Validaciones**: Badge actualizado, mensaje de éxito, producto en carrito

#### ✅ Agregar producto con cantidad personalizada
- **Objetivo**: Verificar agregado con cantidad específica
- **Pasos**: Navegar → Establecer cantidad → Agregar → Verificar
- **Validaciones**: Cantidad respetada, totales correctos

#### ✅ Validar límites de cantidad (1-99)
- **Objetivo**: Verificar límites mínimos y máximos
- **Pasos**: Probar cantidad 0, 100, valores extremos
- **Validaciones**: Límites respetados, valores corregidos

#### ✅ Agregar producto duplicado
- **Objetivo**: Verificar suma de cantidades en lugar de duplicar
- **Pasos**: Agregar mismo producto dos veces
- **Validaciones**: Una sola entrada, cantidad sumada

#### ✅ Validar controles de cantidad
- **Objetivo**: Verificar botones +/-
- **Pasos**: Usar controles, verificar límites
- **Validaciones**: Incremento/decremento correcto

#### ✅ Validar mensajes de confirmación
- **Objetivo**: Verificar feedback al usuario
- **Pasos**: Agregar producto, observar mensajes
- **Validaciones**: Mensajes apropiados, timing correcto

#### ✅ Validar comportamiento AJAX
- **Objetivo**: Verificar operaciones sin recarga
- **Pasos**: Interceptar requests, verificar headers
- **Validaciones**: No recarga, AJAX correcto, actualización dinámica

#### ✅ Múltiples productos diferentes
- **Objetivo**: Verificar agregado de varios productos
- **Pasos**: Agregar 3 productos diferentes
- **Validaciones**: Entradas separadas, totales correctos

### 🔧 03-carrito-gestionar.spec.js

#### ✅ Incrementar cantidad de producto
- **Objetivo**: Verificar incremento desde carrito
- **Pasos**: Ir a carrito → Incrementar → Verificar actualización
- **Validaciones**: Cantidad +1, subtotal actualizado, totales recalculados

#### ✅ Decrementar cantidad sin bajar de 1
- **Objetivo**: Verificar decremento con límite mínimo
- **Pasos**: Decrementar productos con cantidad >1 y =1
- **Validaciones**: Decremento correcto, no baja de 1

#### ✅ Cambiar cantidad directamente
- **Objetivo**: Verificar input directo de cantidad
- **Pasos**: Escribir cantidad en input → Verificar
- **Validaciones**: Cantidad válida aceptada, inválida rechazada

#### ✅ Eliminar producto individual
- **Objetivo**: Verificar eliminación específica
- **Pasos**: Eliminar un producto → Confirmar → Verificar
- **Validaciones**: Producto eliminado, otros intactos, totales actualizados

#### ✅ Vaciar carrito completo
- **Objetivo**: Verificar limpieza total
- **Pasos**: Vaciar carrito → Confirmar
- **Validaciones**: Estado vacío, badge en 0, totales en 0

#### ✅ Validar cálculos de totales
- **Objetivo**: Verificar matemáticas correctas
- **Pasos**: Calcular esperado vs mostrado
- **Validaciones**: Subtotal, impuestos (16%), total, formato moneda

#### ✅ Verificar disponibilidad de productos
- **Objetivo**: Verificar función de verificación
- **Pasos**: Ejecutar verificación → Observar resultado
- **Validaciones**: Sin errores, mensaje confirmación

#### ✅ Navegación desde carrito
- **Objetivo**: Verificar enlaces de navegación
- **Pasos**: Usar "Seguir comprando", carrito flotante
- **Validaciones**: Navegación correcta, estado preservado

#### ✅ Persistencia de sesión
- **Objetivo**: Verificar persistencia entre navegaciones
- **Pasos**: Navegar → Volver → Recargar
- **Validaciones**: Carrito preservado, datos íntegros

#### ✅ Manejo de casos edge y errores
- **Objetivo**: Verificar robustez ante errores
- **Pasos**: Simular errores de servidor
- **Validaciones**: Errores manejados apropiadamente

### 🔄 04-carrito-flujo-completo.spec.js

#### 🎯 Flujo: Comprador decidido
- **Escenario**: Usuario que sabe exactamente qué comprar
- **Flujo**: Llegar → Buscar → Ver → Agregar → Checkout
- **Validaciones**: Flujo rápido, información clara, confirmaciones

#### 🔍 Flujo: Comprador explorador
- **Escenario**: Usuario que compara múltiples productos
- **Flujo**: Explorar → Agregar varios → Comparar → Modificar → Finalizar
- **Validaciones**: Múltiples productos, modificaciones, decisión final

#### 🤔 Flujo: Comprador indeciso
- **Escenario**: Usuario que abandona y vuelve
- **Flujo**: Agregar → Salir → Volver → Continuar
- **Validaciones**: Persistencia, recuperación de sesión

#### 📱 Flujo: Comprador móvil
- **Escenario**: Usuario en dispositivo móvil
- **Flujo**: Viewport móvil → Controles táctiles → Responsividad
- **Validaciones**: Experiencia móvil completa

#### ⚠️ Flujo: Manejo de errores
- **Escenario**: Productos no disponibles o errores
- **Flujo**: Agregar → Error → Manejar → Continuar
- **Validaciones**: Recuperación de errores

#### 📦 Flujo: Comprador bulk
- **Escenario**: Cantidades grandes
- **Flujo**: Agregar cantidades máximas → Verificar límites
- **Validaciones**: Límites respetados, cálculos correctos

#### ⚡ Flujo: Performance
- **Escenario**: Operaciones rápidas
- **Flujo**: Múltiples operaciones sin esperas
- **Validaciones**: Sin race conditions, consistencia

## 🔧 Page Objects y Helpers

### CarritoPage.js
Encapsula todas las interacciones con la página del carrito:
- Navegación y elementos del carrito
- Operaciones de gestión (incrementar, decrementar, eliminar)
- Obtención de datos (items, totales, mensajes)
- Validaciones de estado

### ProductoPage.js
Maneja la página de detalle de producto:
- Información del producto
- Formulario de agregar al carrito
- Controles de cantidad
- Confirmaciones y mensajes

### CarritoHelpers.js
Utilidades para facilitar las pruebas:
- Preparación de datos de prueba
- Cálculos matemáticos
- Validaciones complejas
- Simulación de comportamientos
- Generación de reportes

## 📊 Datos de Prueba

### carrito-test-data.json
Contiene datos estructurados para las pruebas:
- **productos**: Información de álbumes de prueba
- **escenarios**: Casos de prueba predefinidos
- **validaciones**: Reglas de negocio (límites, formatos)
- **mensajes**: Textos esperados en la interfaz

## 🐛 Debugging y Troubleshooting

### Problemas Comunes

#### 1. Laravel no está corriendo
```bash
# Solución
php artisan serve
```

#### 2. Base de datos vacía
```bash
# Ejecutar seeders
php artisan db:seed --class=DiscosRockSimpleSeeder
```

#### 3. Pruebas fallan por timing
```javascript
// Aumentar timeouts en playwright.config.js
timeout: 30000
```

#### 4. Elementos no encontrados
```javascript
// Usar esperas explícitas
await page.waitForSelector('#elemento');
await page.waitForLoadState('networkidle');
```

### Modo Debug
```bash
# Ejecutar con debug visual
npx playwright test --debug --headed

# Con screenshots en fallos
npx playwright test --screenshot=only-on-failure

# Con videos
npx playwright test --video=retain-on-failure
```

## 📈 Reportes y Métricas

### Tipos de Reportes Generados
1. **HTML Report**: Reporte visual interactivo
2. **JSON Report**: Datos estructurados para análisis
3. **JUnit Report**: Compatible con CI/CD
4. **Screenshots**: Capturas en fallos
5. **Videos**: Grabaciones de ejecución

### Métricas Importantes
- **Tiempo de ejecución** por prueba
- **Tasa de éxito/fallo**
- **Cobertura de funcionalidades**
- **Performance de operaciones AJAX**
- **Compatibilidad entre navegadores**

## 🔄 Integración Continua

### GitHub Actions (Ejemplo)
```yaml
name: E2E Carrito Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
      - name: Install dependencies
        run: npm ci
      - name: Install Playwright
        run: npx playwright install
      - name: Start Laravel
        run: php artisan serve &
      - name: Run Carrito Tests
        run: npx playwright test tests/02-carrito-*.spec.js
      - name: Upload reports
        uses: actions/upload-artifact@v3
        with:
          name: playwright-report
          path: playwright-report/
```

## 📚 Mejores Prácticas

### 1. Estructura de Pruebas
- **Arrange-Act-Assert**: Patrón claro en cada test
- **Page Object Model**: Encapsular interacciones UI
- **Data-driven**: Usar fixtures para datos de prueba
- **Descriptive naming**: Nombres claros y específicos

### 2. Manejo de Estado
- **Limpieza**: Limpiar carrito antes de cada test
- **Aislamiento**: Tests independientes entre sí
- **Persistencia**: Validar comportamiento de sesión
- **Rollback**: Restaurar estado después de errores

### 3. Validaciones
- **Múltiples niveles**: UI, datos, cálculos
- **Casos edge**: Límites y situaciones extremas
- **Error handling**: Comportamiento ante fallos
- **Performance**: Tiempos de respuesta aceptables

### 4. Mantenimiento
- **Selectores robustos**: Usar data-testid cuando sea posible
- **Esperas inteligentes**: waitForSelector vs waitForTimeout
- **Logging**: Información útil para debugging
- **Documentación**: Comentarios claros en código

## 🎯 Próximos Pasos

### Funcionalidades Pendientes
- [ ] Pruebas de checkout completo
- [ ] Integración con sistema de pagos
- [ ] Pruebas de cupones y descuentos
- [ ] Validación de stock en tiempo real
- [ ] Pruebas de concurrencia múltiple usuario

### Mejoras Técnicas
- [ ] Paralelización de pruebas
- [ ] Integración con herramientas de monitoreo
- [ ] Pruebas de accesibilidad (a11y)
- [ ] Pruebas de seguridad (XSS, CSRF)
- [ ] Optimización de performance de pruebas

---

## 📞 Soporte

Para dudas o problemas con las pruebas:
1. Revisar esta documentación
2. Verificar logs de Laravel: `storage/logs/laravel.log`
3. Ejecutar en modo debug: `--debug --headed`
4. Revisar reportes HTML generados

**¡Happy Testing! 🚀**