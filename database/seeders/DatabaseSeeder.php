<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entrada;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejemplo en un seeder
        // User::truncate();
        // Entrada::truncate();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //$this->call(UsuariosTableSeeder::class);

        // Crea 10 usuarios aleatorios
        //User::factory(10)->create();

        // Crea 20 entradas aleatorias
        //Entrada::factory(20)->create();

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(DiscosRock90sSeeder::class);
    }
}
