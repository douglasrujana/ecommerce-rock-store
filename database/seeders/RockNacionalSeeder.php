<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class RockNacionalSeeder extends Seeder
{
    public function run(): void
    {
        $discos = [
            [
                'codigo' => 'SE001',
                'nombre' => 'Nada Personal - Soda Stereo',
                'descripcion' => 'Álbum icónico del rock argentino. Incluye "Cuando Pase el Temblor", "Nada Personal" y "Juego de Seducción".',
                'precio' => 26.99,
                'imagen' => 'nada-personal.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/8/8a/Soda_Stereo_-_Nada_Personal.jpg'
            ],
            [
                'codigo' => 'SE002',
                'nombre' => 'Signos - Soda Stereo',
                'descripcion' => 'Obra maestra de Gustavo Cerati. Contiene "Persiana Americana", "En la Ciudad de la Furia" y "Signos".',
                'precio' => 28.99,
                'imagen' => 'signos.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/4/4f/Soda_Stereo_-_Signos.jpg'
            ],
            [
                'codigo' => 'SM001',
                'nombre' => 'El Corazón del Infierno - Sentimiento Muerto',
                'descripcion' => 'Clásico del rock venezolano. Incluye "Ven", "La Danza de las Libélulas" y "Astros".',
                'precio' => 24.99,
                'imagen' => null
            ],
            [
                'codigo' => 'ZT001',
                'nombre' => 'Zapato 3 - Zapato 3',
                'descripcion' => 'Debut del rock venezolano. Contiene "Muriendo de Playa", "Ahora Estoy Aquí" y "Como un Fantasma".',
                'precio' => 23.99,
                'imagen' => null
            ],
            [
                'codigo' => 'CG001',
                'nombre' => 'Clics Modernos - Charly García',
                'descripcion' => 'Obra cumbre del rock argentino. Incluye "Nos Siguen Pegando Abajo", "No Voy en Tren" y "Los Dinosaurios".',
                'precio' => 29.99,
                'imagen' => 'clics-modernos.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/a/a4/Charly_Garc%C3%ADa_-_Clics_Modernos.jpg'
            ],
            [
                'codigo' => 'MD001',
                'nombre' => 'Kind of Blue - Miles Davis',
                'descripcion' => 'Obra maestra del jazz modal. Contiene "So What", "Blue in Green" y "All Blues". Álbum esencial del jazz.',
                'precio' => 32.99,
                'imagen' => 'kind-of-blue.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/9/9c/MilesDavisKindofBlue.jpg'
            ]
        ];

        foreach ($discos as $disco) {
            // Crear producto
            Producto::create([
                'codigo' => $disco['codigo'],
                'nombre' => $disco['nombre'],
                'descripcion' => $disco['descripcion'],
                'precio' => $disco['precio'],
                'imagen' => $disco['imagen']
            ]);

            // Intentar descargar imagen si tiene URL
            if (isset($disco['url']) && $this->descargar($disco['url'], $disco['imagen'])) {
                $this->command->info("✅ {$disco['codigo']}: {$disco['imagen']}");
            } else {
                $this->command->info("📀 {$disco['codigo']}: {$disco['nombre']} (placeholder)");
            }
        }

        $this->command->info('🎸 Rock nacional y jazz agregados!');
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