<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Crear usuario administrador con todos los permisos
     */
    public function run(): void
    {
        // Crear permisos si no existen
        $permissions = [
            // Usuarios
            'user-list',
            'user-create', 
            'user-edit',
            'user-delete',
            
            // Roles
            'rol-list',
            'rol-create',
            'rol-edit', 
            'rol-delete',
            
            // Productos
            'producto-list',
            'producto-create',
            'producto-edit',
            'producto-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear rol de Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Asignar todos los permisos al rol Super Admin
        $superAdminRole->syncPermissions(Permission::all());

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('admin123'),
                'activo' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol Super Admin al usuario
        $admin->assignRole($superAdminRole);

        $this->command->info('✅ Usuario administrador creado exitosamente:');
        $this->command->info('📧 Email: admin@sistema.com');
        $this->command->info('🔑 Password: admin123');
        $this->command->info('👑 Rol: Super Admin');
        $this->command->info('🛡️ Permisos: ' . count($permissions) . ' permisos asignados');
    }
}