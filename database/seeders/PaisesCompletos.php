<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pais;

class PaisesCompletos extends Seeder
{
    public function run(): void
    {
        $paises = [
            ['nombre' => 'Suecia', 'codigo' => 'SWE', 'bandera' => '🇸🇪'],
            ['nombre' => 'Francia', 'codigo' => 'FRA', 'bandera' => '🇫🇷']
        ];

        foreach ($paises as $pais) {
            Pais::firstOrCreate(['codigo' => $pais['codigo']], $pais);
        }

        $this->command->info('🌍 Países completados');
    }
}