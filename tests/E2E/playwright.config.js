/**
 * 🎭 CONFIGURACIÓN PLAYWRIGHT PARA LARAVEL
 * 
 * Configuración optimizada para pruebas E2E en aplicaciones Laravel
 * con soporte para múltiples navegadores y entornos.
 */

const { defineConfig, devices } = require('@playwright/test');

module.exports = defineConfig({
  // Directorio de pruebas E2E
  testDir: './tests',
  
  // Configuración de timeouts
  timeout: 30 * 1000,
  expect: {
    timeout: 5000
  },
  
  // Configuración de ejecución
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 1 : undefined,
  
  // Reportes
  reporter: [
    ['html', { outputFolder: 'tests/E2E/reports/html' }],
    ['json', { outputFile: 'tests/E2E/reports/results.json' }],
    ['junit', { outputFile: 'tests/E2E/reports/results.xml' }]
  ],
  
  // Configuración global
  use: {
    // URL base de la aplicación Laravel
    baseURL: 'http://localhost:8000',
    
    // Configuración de navegador
    headless: true,
    viewport: { width: 1280, height: 720 },
    ignoreHTTPSErrors: true,
    
    // Configuración de video y screenshots
    video: 'retain-on-failure',
    screenshot: 'only-on-failure',
    trace: 'retain-on-failure',
    
    // Headers personalizados
    extraHTTPHeaders: {
      'Accept-Language': 'es-ES,es;q=0.9,en;q=0.8'
    }
  },

  // Proyectos (navegadores)
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
    {
      name: 'firefox',
      use: { ...devices['Desktop Firefox'] },
    },
    {
      name: 'webkit',
      use: { ...devices['Desktop Safari'] },
    },
    // Pruebas móviles
    {
      name: 'Mobile Chrome',
      use: { ...devices['Pixel 5'] },
    },
    {
      name: 'Mobile Safari',
      use: { ...devices['iPhone 12'] },
    },
  ],

  // Servidor web (Laravel)
  webServer: {
    command: 'php artisan serve --port=8000 --env=testing',
    url: 'http://localhost:8000',
    reuseExistingServer: !process.env.CI,
    timeout: 120 * 1000,
    env: {
      APP_ENV: 'testing',
      DB_CONNECTION: 'sqlite',
      DB_DATABASE: 'database/testing.sqlite',
      MAIL_MAILER: 'array'
    }
  },

  // Setup global
  globalSetup: require.resolve('./helpers/global-setup.js'),
  globalTeardown: require.resolve('./helpers/global-teardown.js')
});