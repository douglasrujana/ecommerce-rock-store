<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear los roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $clienteRole = Role::firstOrCreate(['name'=> 'cliente']);

        // Definer Permisos
        $adminPermissions = [
            'user-list', 'user-create', 'user-edit', 'user-delete', 'user-activate',
            'rol-list', 'rol-create', 'rol-edit', 'rol-delete',
            'producto-list', 'prodcuto-create', 'prodcuto-edit', 'producto-delete',
            'pedido-list', 'pedido-anulate'
        ];

        $clientePermissions = ['pedido-view', 'pedido-cancel', 'perfil'];

        // Crear y asignar permisos
        foreach($adminPermissions as $permiso)
        {
            $permission = Permission::firstOrCreate(['name' => $permiso]);
            $adminRole->givePermissionTo($permission);
        }

        foreach($clientePermissions as $permiso)
        {
            $permission = Permission::firstOrCreate(['name' => $permiso]);
            $clienteRole->givePermissionTo($permission);
        }

        // crear usuarios y asignar roles
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@prueba.com'],
            ['name' => 'Admin', 'password' => bcrypt('123456789')]
        );
        $adminUser->assignRole($adminRole);

        //
        $clienteUser = User::firstOrCreate(
            ['email' => 'cliente@prueba.com'],
            ['name' => 'Cliente', 'password' => bcrypt('123456789')]
        );
        // Asignación de rol cliente
        $clienteUser->assignRole($clienteRole);

    }
}
