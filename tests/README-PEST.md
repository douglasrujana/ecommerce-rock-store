# Pruebas con Pest - Recuperación de Contraseña

## Descripción

Este conjunto de pruebas utiliza **Pest PHP** para verificar completamente el flujo de recuperación de contraseña en Laravel, cubriendo:

- ✅ **Rutas y Middleware**: Verificación de acceso y protecciones
- ✅ **Validaciones**: Formato de email, longitud de contraseña, confirmación
- ✅ **Base de Datos**: Creación, actualización y eliminación de tokens
- ✅ **Emails**: Envío y contenido de correos de recuperación
- ✅ **Seguridad**: Tokens válidos/inválidos, protección contra ataques
- ✅ **Integración**: Flujo completo end-to-end

## Instalación de Pest

Si no tienes Pest instalado, ejecuta:

```bash
composer require pestphp/pest --dev
composer require pestphp/pest-plugin-laravel --dev
./vendor/bin/pest --init
```

## Ejecutar Pruebas

### Todas las pruebas de recuperación de contraseña:
```bash
./vendor/bin/pest tests/Feature/PasswordResetPestTest.php
```

### Por grupos específicos:
```bash
# Solo pruebas de validación
./vendor/bin/pest --group=validation

# Solo pruebas de seguridad
./vendor/bin/pest --group=security

# Solo pruebas de base de datos
./vendor/bin/pest --group=database

# Flujo completo end-to-end
./vendor/bin/pest --group=e2e
```

### Con cobertura de código:
```bash
./vendor/bin/pest --coverage
```

### Modo verbose para más detalles:
```bash
./vendor/bin/pest -v
```

## Estructura de las Pruebas

### Fase 1: Solicitud de Recuperación
- `muestra el formulario de solicitud de recuperación de contraseña`
- `permite acceso sin autenticación al formulario de solicitud`

### Fase 2: Envío de Enlace
- `envía enlace de recuperación con email válido`
- `rechaza email inexistente con validación apropiada`
- `valida formato de email correctamente`
- `actualiza token existente en lugar de crear duplicado`

### Fase 3: Formulario de Restablecimiento
- `muestra formulario de restablecimiento con token válido`

### Fase 4: Procesamiento Final
- `restablece contraseña con datos válidos`
- `rechaza token inválido o expirado`
- `valida confirmación de contraseña`
- `valida longitud mínima de contraseña`

### Pruebas de Integración
- `completa el flujo completo de recuperación de contraseña`

## Características de las Pruebas

### 🎯 **Elegantes y Expresivas**
```php
it('envía enlace de recuperación con email válido', function () {
    // Código limpio y legible
});
```

### 🚀 **Modernas y Eficientes**
- Uso de `beforeEach()` para configuración
- Datasets para pruebas parametrizadas
- Helpers personalizados
- Expectativas expresivas

### 🔒 **Agnósticas y Seguras**
- Base de datos en memoria (SQLite)
- Mail fake para interceptar correos
- Aislamiento completo entre pruebas
- No afecta datos reales

### 📚 **Documentadas**
- Comentarios explicativos en cada fase
- Grupos organizados por funcionalidad
- Flujo de trabajo claramente definido

## Grupos de Pruebas

| Grupo | Descripción |
|-------|-------------|
| `password-reset` | Todas las pruebas de recuperación |
| `validation` | Pruebas de validación de datos |
| `security` | Pruebas de seguridad y protección |
| `database` | Pruebas de operaciones en BD |
| `email` | Pruebas de envío de correos |
| `ui` | Pruebas de interfaz de usuario |
| `routes` | Pruebas de rutas y middleware |
| `middleware` | Pruebas específicas de middleware |
| `integration` | Pruebas de integración |
| `e2e` | Pruebas end-to-end completas |

## Configuración del Entorno

Las pruebas utilizan:
- **Base de datos**: SQLite en memoria (`:memory:`)
- **Mail**: Driver `array` (fake)
- **Cache**: Driver `array`
- **Session**: Driver `array`
- **Queue**: Conexión `sync`

## Flujo de Trabajo Completo

```
1. Usuario → GET /password/reset (Formulario)
2. Usuario → POST /password/email (Validación + Token + Email)
3. Usuario → GET /password/reset/{token} (Formulario reset)
4. Usuario → POST /password/reset (Validación + Update + Redirect)
5. Usuario → POST /login (Verificación nueva contraseña)
```

## Comandos Útiles

```bash
# Ejecutar solo una prueba específica
./vendor/bin/pest --filter="envía enlace de recuperación"

# Ejecutar con modo watch (re-ejecuta al cambiar archivos)
./vendor/bin/pest --watch

# Generar reporte HTML
./vendor/bin/pest --coverage-html=coverage

# Ejecutar en paralelo (más rápido)
./vendor/bin/pest --parallel
```

## Métricas de Calidad

Las pruebas verifican:
- ✅ 100% de cobertura de rutas de recuperación
- ✅ Todas las validaciones de entrada
- ✅ Manejo correcto de errores
- ✅ Seguridad de tokens
- ✅ Integridad de base de datos
- ✅ Funcionalidad de emails