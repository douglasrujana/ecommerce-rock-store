// 🎸 CONFIGURACIÓN PLAYWRIGHT PARA ECOMMERCE MUSICAL
import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: '../tests',
  timeout: 30000,
  retries: process.env.CI ? 2 : 1,
  workers: process.env.CI ? 1 : 3,
  
  reporter: [
    ['html', { outputFolder: '../reports/html' }],
    ['json', { outputFile: '../reports/results.json' }]
  ],
  
  use: {
    baseURL: 'http://localhost:8000',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    trace: 'retain-on-failure',
    viewport: { width: 1280, height: 720 }
  },

  projects: [
    { name: 'Desktop Chrome', use: { ...devices['Desktop Chrome'] } },
    { name: 'Mobile Chrome', use: { ...devices['Pixel 5'] } }
  ]
});