<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Categoria, Decada, Pais, Producto};

class CategorizacionSeeder extends Seeder
{
    public function run(): void
    {
        // 🎸 CREAR CATEGORÍAS
        $categorias = [
            ['nombre' => 'Rock Clásico', 'descripcion' => 'Rock de los 60s y 70s', 'color' => '#dc3545'],
            ['nombre' => 'Grunge', 'descripcion' => 'Rock alternativo de los 90s', 'color' => '#6c757d'],
            ['nombre' => 'Punk', 'descripcion' => 'Punk rock y hardcore', 'color' => '#fd7e14'],
            ['nombre' => 'New Wave', 'descripcion' => 'Synth-pop y post-punk', 'color' => '#6f42c1'],
            ['nombre' => 'Britpop', 'descripcion' => 'Rock británico de los 90s', 'color' => '#0d6efd'],
            ['nombre' => 'Jazz', 'descripcion' => 'Jazz clásico y fusion', 'color' => '#198754'],
            ['nombre' => 'Rock Nacional', 'descripcion' => 'Rock latinoamericano', 'color' => '#ffc107'],
            ['nombre' => 'Indie Rock', 'descripcion' => 'Rock independiente', 'color' => '#20c997']
        ];

        foreach ($categorias as $cat) {
            Categoria::create($cat);
        }

        // 📅 CREAR DÉCADAS
        $decadas = [
            ['nombre' => '1960s', 'inicio' => 1960, 'fin' => 1969],
            ['nombre' => '1970s', 'inicio' => 1970, 'fin' => 1979],
            ['nombre' => '1980s', 'inicio' => 1980, 'fin' => 1989],
            ['nombre' => '1990s', 'inicio' => 1990, 'fin' => 1999]
        ];

        foreach ($decadas as $dec) {
            Decada::create($dec);
        }

        // 🌍 CREAR PAÍSES
        $paises = [
            ['nombre' => 'Estados Unidos', 'codigo' => 'USA', 'bandera' => '🇺🇸'],
            ['nombre' => 'Reino Unido', 'codigo' => 'UK', 'bandera' => '🇬🇧'],
            ['nombre' => 'Argentina', 'codigo' => 'ARG', 'bandera' => '🇦🇷'],
            ['nombre' => 'Venezuela', 'codigo' => 'VEN', 'bandera' => '🇻🇪']
        ];

        foreach ($paises as $pais) {
            Pais::create($pais);
        }

        $this->command->info('✅ Categorías, décadas y países creados');
        $this->asignarCategorias();
    }

    private function asignarCategorias()
    {
        // Obtener IDs
        $rock = Categoria::where('nombre', 'Rock Clásico')->first()->id;
        $grunge = Categoria::where('nombre', 'Grunge')->first()->id;
        $punk = Categoria::where('nombre', 'Punk')->first()->id;
        $newwave = Categoria::where('nombre', 'New Wave')->first()->id;
        $britpop = Categoria::where('nombre', 'Britpop')->first()->id;
        $jazz = Categoria::where('nombre', 'Jazz')->first()->id;
        $nacional = Categoria::where('nombre', 'Rock Nacional')->first()->id;
        $indie = Categoria::where('nombre', 'Indie Rock')->first()->id;

        $s60 = Decada::where('nombre', '1960s')->first()->id;
        $s70 = Decada::where('nombre', '1970s')->first()->id;
        $s80 = Decada::where('nombre', '1980s')->first()->id;
        $s90 = Decada::where('nombre', '1990s')->first()->id;

        $usa = Pais::where('codigo', 'USA')->first()->id;
        $uk = Pais::where('codigo', 'UK')->first()->id;
        $arg = Pais::where('codigo', 'ARG')->first()->id;
        $ven = Pais::where('codigo', 'VEN')->first()->id;

        // 🎸 ASIGNAR CATEGORÍAS A PRODUCTOS
        $asignaciones = [
            // Beatles
            'BT001' => ['categoria' => $rock, 'decada' => $s60, 'pais' => $uk, 'año' => 1969],
            'BT002' => ['categoria' => $rock, 'decada' => $s60, 'pais' => $uk, 'año' => 1967],
            
            // Pink Floyd
            'PF001' => ['categoria' => $rock, 'decada' => $s70, 'pais' => $uk, 'año' => 1973],
            'PF002' => ['categoria' => $rock, 'decada' => $s70, 'pais' => $uk, 'año' => 1979],
            'PF003' => ['categoria' => $rock, 'decada' => $s70, 'pais' => $uk, 'año' => 1975],
            'PF004' => ['categoria' => $rock, 'decada' => $s60, 'pais' => $uk, 'año' => 1967],
            
            // Grunge
            'NIR001' => ['categoria' => $grunge, 'decada' => $s90, 'pais' => $usa, 'año' => 1991],
            'PJ001' => ['categoria' => $grunge, 'decada' => $s90, 'pais' => $usa, 'año' => 1991],
            'SG001' => ['categoria' => $grunge, 'decada' => $s90, 'pais' => $usa, 'año' => 1991],
            'SG002' => ['categoria' => $grunge, 'decada' => $s90, 'pais' => $usa, 'año' => 1994],
            'AIC001' => ['categoria' => $grunge, 'decada' => $s90, 'pais' => $usa, 'año' => 1992],
            
            // Punk
            'RA001' => ['categoria' => $punk, 'decada' => $s70, 'pais' => $usa, 'año' => 1976],
            'RA002' => ['categoria' => $punk, 'decada' => $s70, 'pais' => $usa, 'año' => 1977],
            'GD001' => ['categoria' => $punk, 'decada' => $s90, 'pais' => $usa, 'año' => 1994],
            
            // New Wave
            'TC001' => ['categoria' => $newwave, 'decada' => $s80, 'pais' => $uk, 'año' => 1989],
            'TC002' => ['categoria' => $newwave, 'decada' => $s80, 'pais' => $uk, 'año' => 1985],
            'DM001' => ['categoria' => $newwave, 'decada' => $s90, 'pais' => $uk, 'año' => 1990],
            'DM002' => ['categoria' => $newwave, 'decada' => $s80, 'pais' => $uk, 'año' => 1987],
            
            // Britpop
            'OA001' => ['categoria' => $britpop, 'decada' => $s90, 'pais' => $uk, 'año' => 1994],
            'OA002' => ['categoria' => $britpop, 'decada' => $s90, 'pais' => $uk, 'año' => 1995],
            
            // Jazz
            'MD001' => ['categoria' => $jazz, 'decada' => $s60, 'pais' => $usa, 'año' => 1959],
            
            // Rock Nacional
            'SE001' => ['categoria' => $nacional, 'decada' => $s80, 'pais' => $arg, 'año' => 1985],
            'SE002' => ['categoria' => $nacional, 'decada' => $s80, 'pais' => $arg, 'año' => 1986],
            'CG001' => ['categoria' => $nacional, 'decada' => $s80, 'pais' => $arg, 'año' => 1983],
            'SM001' => ['categoria' => $nacional, 'decada' => $s80, 'pais' => $ven, 'año' => 1985],
            'ZT001' => ['categoria' => $nacional, 'decada' => $s80, 'pais' => $ven, 'año' => 1984],
            
            // Indie Rock
            'SY001' => ['categoria' => $indie, 'decada' => $s80, 'pais' => $usa, 'año' => 1988],
            'SY002' => ['categoria' => $indie, 'decada' => $s90, 'pais' => $usa, 'año' => 1990],
            'DJ001' => ['categoria' => $indie, 'decada' => $s80, 'pais' => $usa, 'año' => 1987],
            'TS001' => ['categoria' => $indie, 'decada' => $s80, 'pais' => $uk, 'año' => 1986],
            'TS002' => ['categoria' => $indie, 'decada' => $s80, 'pais' => $uk, 'año' => 1985]
        ];

        foreach ($asignaciones as $codigo => $data) {
            Producto::where('codigo', $codigo)->update([
                'categoria_id' => $data['categoria'],
                'decada_id' => $data['decada'],
                'pais_id' => $data['pais'],
                'año' => $data['año']
            ]);
        }

        $this->command->info('🎸 Productos categorizados correctamente');
    }
}