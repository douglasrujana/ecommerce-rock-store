# Pruebas E2E con Playwright

Este directorio contiene pruebas end-to-end (e2e) utilizando Playwright para probar la funcionalidad de la aplicación en un navegador real.

## Estructura

- `e2e/`: Contiene las pruebas e2e escritas con Playwright
- `playwright.config.js`: Configuración de Playwright

## Instalación

Para instalar las dependencias necesarias:

```bash
npm install
npx playwright install
```

## Ejecutar pruebas

Para ejecutar las pruebas e2e:

```bash
npm run test:e2e
```

Para ejecutar las pruebas con la interfaz de usuario de Playwright:

```bash
npm run test:e2e:ui
```

## Pruebas de recuperación de contraseña

La prueba `password-reset.spec.js` verifica el flujo completo de recuperación de contraseña:

1. Solicitar un restablecimiento de contraseña
2. Verificar que se envía un correo electrónico
3. Usar el token para restablecer la contraseña
4. Iniciar sesión con la nueva contraseña

### Notas importantes

- Estas pruebas asumen que el servidor está en ejecución en `http://localhost:8000`
- Se requiere una base de datos de prueba configurada
- Las pruebas crean usuarios de prueba automáticamente