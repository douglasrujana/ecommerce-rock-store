<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class BeatlesRockSeeder extends Seeder
{
    public function run(): void
    {
        $discos = [
            [
                'codigo' => 'BT001',
                'nombre' => 'Abbey Road - The Beatles',
                'descripcion' => 'Último álbum grabado por The Beatles. Incluye "Come Together", "Something" y "Here Comes the Sun".',
                'precio' => 31.99,
                'imagen' => 'abbey-road.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/4/42/Beatles_-_Abbey_Road.jpg'
            ],
            [
                'codigo' => 'BT002',
                'nombre' => 'Sgt. Pepper - The Beatles',
                'descripcion' => 'Obra maestra psicodélica. Contiene "Lucy in the Sky with Diamonds" y "A Day in the Life".',
                'precio' => 33.99,
                'imagen' => 'sgt-pepper.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/5/50/Sgt._Pepper%27s_Lonely_Hearts_Club_Band.jpg'
            ],
            [
                'codigo' => 'LZ001',
                'nombre' => 'Led Zeppelin IV - Led Zeppelin',
                'descripcion' => 'Álbum icónico del hard rock. Incluye "Stairway to Heaven", "Black Dog" y "Rock and Roll".',
                'precio' => 29.99,
                'imagen' => 'zeppelin-iv.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/2/26/Led_Zeppelin_-_Led_Zeppelin_IV.jpg'
            ],
            [
                'codigo' => 'QU001',
                'nombre' => 'A Night at the Opera - Queen',
                'descripcion' => 'Obra maestra de Queen. Contiene "Bohemian Rhapsody", "Love of My Life" y "You\'re My Best Friend".',
                'precio' => 28.99,
                'imagen' => 'queen-opera.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/4/4d/Queen_A_Night_At_The_Opera.png'
            ],
            [
                'codigo' => 'AC001',
                'nombre' => 'Back in Black - AC/DC',
                'descripcion' => 'Álbum tributo a Bon Scott. Incluye "Back in Black", "You Shook Me All Night Long" y "Hells Bells".',
                'precio' => 26.99,
                'imagen' => 'back-in-black.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/commons/9/92/ACDC_Back_in_Black.png'
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

            // Intentar descargar imagen
            if ($this->descargar($disco['url'], $disco['imagen'])) {
                $this->command->info("✅ {$disco['codigo']}: {$disco['imagen']}");
            } else {
                // Si falla, quitar imagen del producto
                Producto::where('codigo', $disco['codigo'])->update(['imagen' => null]);
                $this->command->warn("❌ {$disco['codigo']}: Sin imagen");
            }
        }

        $this->command->info('🎸 Clásicos del rock agregados!');
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