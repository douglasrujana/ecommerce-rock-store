/**
 * 🛠️ HELPERS DE BASE DE DATOS PARA E2E
 * 
 * Funciones para manejar datos de prueba sin APIs complejas
 */

const { exec } = require('child_process');
const { promisify } = require('util');

const execAsync = promisify(exec);

/**
 * Ejecutar comando Artisan
 */
async function artisan(command) {
  try {
    const { stdout, stderr } = await execAsync(`php artisan ${command} --env=testing`);
    return { success: true, output: stdout, error: stderr };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * Preparar base de datos limpia
 */
async function freshDatabase() {
  return await artisan('migrate:fresh');
}

/**
 * Ejecutar seeders específicos
 */
async function seedDatabase(seeder = 'TestingSeeder') {
  return await artisan(`db:seed --class=${seeder}`);
}

/**
 * Crear usuario usando Tinker
 */
async function createUserWithTinker(userData) {
  const command = `tinker --execute="
    \\App\\Models\\User::create([
      'name' => '${userData.name}',
      'email' => '${userData.email}',
      'password' => \\Hash::make('${userData.password}'),
      'activo' => ${userData.activo || 1}
    ]);
  "`;
  
  return await artisan(command);
}

/**
 * Obtener token de reset usando Tinker
 */
async function getResetTokenWithTinker(email) {
  const command = `tinker --execute="
    \\$token = \\DB::table('password_reset_tokens')
      ->where('email', '${email}')
      ->first();
    echo \\$token ? \\$token->token : 'null';
  "`;
  
  const result = await artisan(command);
  return result.success ? result.output.trim() : null;
}

/**
 * Limpiar datos específicos
 */
async function cleanupUser(email) {
  const command = `tinker --execute="
    \\App\\Models\\User::where('email', '${email}')->delete();
    \\DB::table('password_reset_tokens')->where('email', '${email}')->delete();
  "`;
  
  return await artisan(command);
}

/**
 * Setup completo para pruebas
 */
async function setupTestEnvironment() {
  console.log('🔄 Preparando entorno de testing...');
  
  // 1. Base de datos limpia
  await freshDatabase();
  console.log('✅ Base de datos limpia');
  
  // 2. Ejecutar seeders
  await seedDatabase();
  console.log('✅ Datos de prueba creados');
  
  return true;
}

/**
 * Limpieza completa después de pruebas
 */
async function teardownTestEnvironment() {
  console.log('🧹 Limpiando entorno de testing...');
  await freshDatabase();
  console.log('✅ Entorno limpio');
}

module.exports = {
  artisan,
  freshDatabase,
  seedDatabase,
  createUserWithTinker,
  getResetTokenWithTinker,
  cleanupUser,
  setupTestEnvironment,
  teardownTestEnvironment
};