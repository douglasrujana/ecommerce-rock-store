/**
 * 🌍 SETUP GLOBAL PARA PLAYWRIGHT
 * 
 * Se ejecuta una vez antes de todas las pruebas
 */

const { setupTestEnvironment } = require('./database-helpers');

async function globalSetup() {
  console.log('🚀 Iniciando setup global de Playwright...');
  
  try {
    // Preparar entorno de testing
    await setupTestEnvironment();
    
    console.log('✅ Setup global completado');
    return true;
  } catch (error) {
    console.error('❌ Error en setup global:', error);
    throw error;
  }
}

module.exports = globalSetup;