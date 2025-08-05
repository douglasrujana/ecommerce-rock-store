<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class NewWaveBritpopSeeder extends Seeder
{
    public function run(): void
    {
        $discos = [
            [
                'codigo' => 'TC001',
                'nombre' => 'Disintegration - The Cure',
                'descripcion' => 'Obra maestra gótica de The Cure. Incluye "Pictures of You", "Lullaby" y "Close to Me".',
                'precio' => 27.99,
                'imagen' => 'disintegration.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/9/9e/The_Cure_-_Disintegration.png'
            ],
            [
                'codigo' => 'TC002',
                'nombre' => 'The Head on the Door - The Cure',
                'descripcion' => 'Álbum icónico de los 80s. Contiene "In Between Days", "Close to Me" y "Push".',
                'precio' => 25.99,
                'imagen' => 'head-on-door.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/7/7c/The_Cure_-_The_Head_on_the_Door.jpg'
            ],
            [
                'codigo' => 'DM001',
                'nombre' => 'Violator - Depeche Mode',
                'descripcion' => 'Obra cumbre del synth-pop. Incluye "Personal Jesus", "Enjoy the Silence" y "Policy of Truth".',
                'precio' => 28.99,
                'imagen' => 'violator.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/4/42/Depeche_Mode_-_Violator.png'
            ],
            [
                'codigo' => 'DM002',
                'nombre' => 'Music for the Masses - Depeche Mode',
                'descripcion' => 'Álbum previo a Violator. Contiene "Strangelove", "Never Let Me Down Again" y "Behind the Wheel".',
                'precio' => 26.99,
                'imagen' => 'music-masses.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/1/15/Depeche_Mode_-_Music_for_the_Masses.jpg'
            ],
            [
                'codigo' => 'OA001',
                'nombre' => 'Definitely Maybe - Oasis',
                'descripcion' => 'Debut explosivo del britpop. Incluye "Live Forever", "Rock \'n\' Roll Star" y "Supersonic".',
                'precio' => 24.99,
                'imagen' => 'definitely-maybe.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/5/50/Definitely_Maybe.jpg'
            ],
            [
                'codigo' => 'OA002',
                'nombre' => '(What\'s the Story) Morning Glory? - Oasis',
                'descripcion' => 'Segundo álbum de Oasis. Contiene "Wonderwall", "Don\'t Look Back in Anger" y "Champagne Supernova".',
                'precio' => 26.99,
                'imagen' => 'morning-glory.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/4/4a/Oasis_-_Morning_Glory.jpg'
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
                Producto::where('codigo', $disco['codigo'])->update(['imagen' => null]);
                $this->command->info("📀 {$disco['codigo']}: {$disco['nombre']} (placeholder)");
            }
        }

        $this->command->info('🎸 New wave y britpop agregados!');
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