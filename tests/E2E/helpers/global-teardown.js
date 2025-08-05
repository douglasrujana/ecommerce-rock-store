/**
 * 🧹 TEARDOWN GLOBAL PARA PLAYWRIGHT
 * 
 * Se ejecuta una vez después de todas las pruebas
 */

const { teardownTestEnvironment } = require('./database-helpers');

async function globalTeardown() {
  console.log('🧹 Iniciando teardown global de Playwright...');
  
  try {
    // Limpiar entorno de testing
    await teardownTestEnvironment();
    
    console.log('✅ Teardown global completado');
  } catch (error) {
    console.error('❌ Error en teardown global:', error);
    // No lanzar error para no fallar las pruebas
  }
}

module.exports = globalTeardown;