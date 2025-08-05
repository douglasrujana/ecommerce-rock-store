<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Categoria, Decada, Pais, Producto};
use Illuminate\Support\Facades\Http;

class HistoriaMusicaCompleta extends Seeder
{
    public function run(): void
    {
        // 📅 CREAR DÉCADAS COMPLETAS
        $decadas = [
            ['nombre' => '1920s', 'inicio' => 1920, 'fin' => 1929],
            ['nombre' => '1930s', 'inicio' => 1930, 'fin' => 1939],
            ['nombre' => '1940s', 'inicio' => 1940, 'fin' => 1949],
            ['nombre' => '1950s', 'inicio' => 1950, 'fin' => 1959],
            ['nombre' => '2000s', 'inicio' => 2000, 'fin' => 2009],
            ['nombre' => '2010s', 'inicio' => 2010, 'fin' => 2019],
            ['nombre' => '2020s', 'inicio' => 2020, 'fin' => 2029]
        ];

        foreach ($decadas as $dec) {
            Decada::firstOrCreate(['nombre' => $dec['nombre']], $dec);
        }

        // 🎸 NUEVAS CATEGORÍAS
        $nuevasCategorias = [
            ['nombre' => 'Blues', 'descripcion' => 'Blues clásico y eléctrico', 'color' => '#4a90e2'],
            ['nombre' => 'Metal', 'descripcion' => 'Heavy metal y subgéneros', 'color' => '#000000'],
            ['nombre' => 'Alternative Rock', 'descripcion' => 'Rock alternativo moderno', 'color' => '#e74c3c'],
            ['nombre' => 'Electronic Rock', 'descripcion' => 'Rock electrónico y synthwave', 'color' => '#9b59b6']
        ];

        foreach ($nuevasCategorias as $cat) {
            Categoria::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }

        $this->command->info('✅ Décadas y categorías actualizadas');
        $this->crearProductosHistoricos();
    }

    private function crearProductosHistoricos()
    {
        $productos = [
            // 🎺 JAZZ CLÁSICO (1920s-1950s)
            [
                'codigo' => 'LA001',
                'nombre' => 'Hot Five and Seven - Louis Armstrong',
                'descripcion' => 'Grabaciones pioneras del jazz. Incluye "What a Wonderful World" y "Hello Dolly".',
                'precio' => 39.99,
                'categoria' => 'Jazz',
                'decada' => '1920s',
                'pais' => 'Estados Unidos',
                'año' => 1925,
                'imagen' => 'louis-armstrong.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/commons/0/0e/Louis_Armstrong_restored.jpg'
            ],
            [
                'codigo' => 'DD001',
                'nombre' => 'Ellington at Newport - Duke Ellington',
                'descripcion' => 'Concierto histórico en Newport. Incluye "Caravan" y "Take Five".',
                'precio' => 34.99,
                'categoria' => 'Jazz',
                'decada' => '1950s',
                'pais' => 'Estados Unidos',
                'año' => 1956,
                'imagen' => null
            ],

            // 🎸 BLUES (1930s-1950s)
            [
                'codigo' => 'RJ001',
                'nombre' => 'King of the Delta Blues - Robert Johnson',
                'descripcion' => 'Leyenda del blues del Delta. Incluye "Cross Road Blues" y "Sweet Home Chicago".',
                'precio' => 29.99,
                'categoria' => 'Blues',
                'decada' => '1930s',
                'pais' => 'Estados Unidos',
                'año' => 1936,
                'imagen' => null
            ],
            [
                'codigo' => 'BB001',
                'nombre' => 'Live at the Regal - B.B. King',
                'descripcion' => 'Blues eléctrico en vivo. Contiene "The Thrill Is Gone" y "Sweet Little Angel".',
                'precio' => 27.99,
                'categoria' => 'Blues',
                'decada' => '1960s',
                'pais' => 'Estados Unidos',
                'año' => 1965,
                'imagen' => 'bb-king-regal.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/f/f5/Live_at_the_Regal.jpg'
            ],

            // 🤘 METAL (2000s-2020s)
            [
                'codigo' => 'MT001',
                'nombre' => 'Blackwater Park - Opeth',
                'descripcion' => 'Metal progresivo sueco. Incluye "The Drapery Falls" y "Bleak".',
                'precio' => 26.99,
                'categoria' => 'Metal',
                'decada' => '2000s',
                'pais' => 'Reino Unido',
                'año' => 2001,
                'imagen' => 'blackwater-park.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/b/b3/Opeth-Blackwater_Park.jpg'
            ],
            [
                'codigo' => 'GJ001',
                'nombre' => 'From Mars to Sirius - Gojira',
                'descripcion' => 'Metal técnico francés. Contiene "Flying Whales" y "The Heaviest Matter".',
                'precio' => 25.99,
                'categoria' => 'Metal',
                'decada' => '2000s',
                'pais' => 'Estados Unidos',
                'año' => 2005,
                'imagen' => null
            ],

            // 🎵 ALTERNATIVE ROCK (2000s-2020s)
            [
                'codigo' => 'AM002',
                'nombre' => 'AM - Arctic Monkeys',
                'descripcion' => 'Rock alternativo británico. Incluye "Do I Wanna Know?" y "R U Mine?".',
                'precio' => 24.99,
                'categoria' => 'Alternative Rock',
                'decada' => '2010s',
                'pais' => 'Reino Unido',
                'año' => 2013,
                'imagen' => 'am-arctic-monkeys.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/0/0b/Arctic_Monkeys_-_AM.png'
            ],
            [
                'codigo' => 'TV001',
                'nombre' => 'Blurryface - Twenty One Pilots',
                'descripcion' => 'Rock alternativo moderno. Contiene "Stressed Out" y "Heathens".',
                'precio' => 23.99,
                'categoria' => 'Alternative Rock',
                'decada' => '2010s',
                'pais' => 'Estados Unidos',
                'año' => 2015,
                'imagen' => null
            ],

            // 🎹 ELECTRONIC ROCK (2020s)
            [
                'codigo' => 'MW001',
                'nombre' => 'Simulation Theory - Muse',
                'descripcion' => 'Rock electrónico futurista. Incluye "Pressure" y "Algorithm".',
                'precio' => 22.99,
                'categoria' => 'Electronic Rock',
                'decada' => '2010s',
                'pais' => 'Reino Unido',
                'año' => 2018,
                'imagen' => 'simulation-theory.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/4/4c/Muse_-_Simulation_Theory.png'
            ]
        ];

        foreach ($productos as $prod) {
            // Obtener IDs
            $categoria = Categoria::where('nombre', $prod['categoria'])->first();
            $decada = Decada::where('nombre', $prod['decada'])->first();
            $pais = Pais::where('nombre', $prod['pais'])->first();

            // Crear producto
            $producto = Producto::create([
                'codigo' => $prod['codigo'],
                'nombre' => $prod['nombre'],
                'descripcion' => $prod['descripcion'],
                'precio' => $prod['precio'],
                'categoria_id' => $categoria?->id,
                'decada_id' => $decada?->id,
                'pais_id' => $pais?->id,
                'año' => $prod['año'],
                'imagen' => $prod['imagen']
            ]);

            // Descargar imagen si existe URL
            if (isset($prod['url']) && $prod['imagen']) {
                if ($this->descargar($prod['url'], $prod['imagen'])) {
                    $this->command->info("✅ {$prod['codigo']}: {$prod['imagen']}");
                } else {
                    $producto->update(['imagen' => null]);
                    $this->command->info("📀 {$prod['codigo']}: Sin imagen");
                }
            } else {
                $this->command->info("📀 {$prod['codigo']}: {$prod['nombre']}");
            }
        }

        $this->command->info('🎸 Historia musical completa agregada!');
    }

    private function descargar($url, $filename)
    {
        try {
            $response = Http::timeout(10)->get($url);
            if ($response->successful()) {
                file_put_contents(public_path('uploads/productos/' . $filename), $response->body());
                return true;
            }
        } catch (\Exception $e) {
            // Silencioso
        }
        return false;
    }
}