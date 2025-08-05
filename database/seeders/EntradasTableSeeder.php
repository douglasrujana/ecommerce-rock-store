<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entrada;

class EntradasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Entrada::create(
        //     [
        //         'user_id' => 2,
        //         'titulo' => "Segundo titulo",
        //         'imagen' => 'imagen_2.png',
        //         'tag' => 'Etiqueta_2',
        //         'contenido' => "Segundo contenido"
        //     ]
        // );
        //
        // Entrada::create(
        //     [
        //         'user_id' => 3,
        //         'titulo' => "Tercel titulo",
        //         'imagen' => 'imagen_3.png',
        //         'tag' => 'Etiqueta_3',
        //         'contenido' => "Tercer contenido"
        //     ]
        // );
        // LLamar a todos los sseder
        //$this->call(EntradasTableSeeder::class);
        Entrada::factory()->count(100)->ceate();
    }
}
