<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DiscosRock90sSeeder extends Seeder
{
    /**
     * 🎸 SEEDER DE DISCOS DE ROCK DE LOS 90s
     * 
     * Incluye álbumes icónicos con imágenes reales descargadas
     */
    public function run(): void
    {
        $discos = [
            [
                'codigo' => 'NIR001',
                'nombre' => 'Nevermind - Nirvana',
                'descripcion' => 'Álbum revolucionario que definió el grunge. Incluye "Smells Like Teen Spirit", "Come As You Are" y "Lithium".',
                'precio' => 24.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/b/b7/NirvanaNevermindalbumcover.jpg',
                'imagen' => 'nevermind-nirvana.jpg'
            ],
            [
                'codigo' => 'PJ001',
                'nombre' => 'Ten - Pearl Jam',
                'descripcion' => 'Debut épico de Pearl Jam. Contiene "Alive", "Even Flow" y "Jeremy". Grunge en su máxima expresión.',
                'precio' => 22.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/2/2d/PearlJam-Ten2.jpg',
                'imagen' => 'ten-pearl-jam.jpg'
            ],
            [
                'codigo' => 'SG001',
                'nombre' => 'Badmotorfinger - Soundgarden',
                'descripcion' => 'Obra maestra del metal alternativo. Incluye "Rusty Cage", "Outshined" y "Jesus Christ Pose".',
                'precio' => 21.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/a/a9/Soundgarden_-_Badmotorfinger.jpg',
                'imagen' => 'badmotorfinger-soundgarden.jpg'
            ],
            [
                'codigo' => 'AIC001',
                'nombre' => 'Dirt - Alice in Chains',
                'descripcion' => 'Álbum oscuro y poderoso. Contiene "Them Bones", "Man in the Box" y "Rooster". Grunge melancólico.',
                'precio' => 23.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/9/95/AliceInChainsDirt.jpg',
                'imagen' => 'dirt-alice-in-chains.jpg'
            ],
            [
                'codigo' => 'SP001',
                'nombre' => 'Siamese Dream - Smashing Pumpkins',
                'descripcion' => 'Rock alternativo atmosférico. Incluye "Today", "Cherub Rock" y "Disarm". Producción impecable.',
                'precio' => 25.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/9/9f/Siamese_Dream.jpg',
                'imagen' => 'siamese-dream-pumpkins.jpg'
            ],
            [
                'codigo' => 'SG002',
                'nombre' => 'Superunknown - Soundgarden',
                'descripcion' => 'Obra cumbre de Soundgarden. Contiene "Black Hole Sun", "Spoonman" y "Fell on Black Days".',
                'precio' => 26.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/3/3a/Soundgarden_-_Superunknown.jpg',
                'imagen' => 'superunknown-soundgarden.jpg'
            ],
            [
                'codigo' => 'GD001',
                'nombre' => 'Dookie - Green Day',
                'descripcion' => 'Punk rock accesible y pegadizo. Incluye "Longview", "Basket Case" y "When I Come Around".',
                'precio' => 19.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/2/22/Dookie.jpg',
                'imagen' => 'dookie-green-day.jpg'
            ],
            [
                'codigo' => 'SP002',
                'nombre' => 'Mellon Collie - Smashing Pumpkins',
                'descripcion' => 'Álbum doble épico. Contiene "1979", "Tonight Tonight" y "Bullet with Butterfly Wings".',
                'precio' => 34.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/1/1d/SmashingPumpkins-MellonCollie.jpg',
                'imagen' => 'mellon-collie-pumpkins.jpg'
            ],
            [
                'codigo' => 'BCK001',
                'nombre' => 'Odelay - Beck',
                'descripcion' => 'Rock experimental y ecléctico. Incluye "Where It\'s At", "Devils Haircut" y "The New Pollution".',
                'precio' => 23.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/4/42/Beck_-_Odelay.png',
                'imagen' => 'odelay-beck.jpg'
            ],
            [
                'codigo' => 'RH001',
                'nombre' => 'OK Computer - Radiohead',
                'descripcion' => 'Obra maestra del rock alternativo. Contiene "Paranoid Android", "Karma Police" y "No Surprises".',
                'precio' => 27.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/b/ba/Radioheadokcomputer.png',
                'imagen' => 'ok-computer-radiohead.jpg'
            ],
            [
                'codigo' => 'AM001',
                'nombre' => 'Jagged Little Pill - Alanis Morissette',
                'descripcion' => 'Rock alternativo con actitud. Incluye "You Oughta Know", "Ironic" y "Hand in My Pocket".',
                'precio' => 21.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/3/3c/Alanis_Morissette_-_Jagged_Little_Pill.png',
                'imagen' => 'jagged-little-pill.jpg'
            ],
            [
                'codigo' => 'LV001',
                'nombre' => 'Throwing Copper - Live',
                'descripcion' => 'Rock alternativo espiritual. Contiene "Lightning Crashes", "Selling the Drama" y "I Alone".',
                'precio' => 20.99,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/en/4/4f/Live_Throwing_Copper.jpg',
                'imagen' => 'throwing-copper-live.jpg'
            ]
        ];

        foreach ($discos as $disco) {
            // Descargar imagen
            $this->descargarImagen($disco['imagen_url'], $disco['imagen']);
            
            // Crear producto
            Producto::create([
                'codigo' => $disco['codigo'],
                'nombre' => $disco['nombre'],
                'descripcion' => $disco['descripcion'],
                'precio' => $disco['precio'],
                'imagen' => $disco['imagen']
            ]);
        }

        $this->command->info('✅ Se crearon ' . count($discos) . ' discos de rock de los 90s');
    }

    /**
     * 📥 DESCARGAR IMAGEN DESDE URL
     */
    private function descargarImagen($url, $filename)
    {
        try {
            $response = Http::get($url);
            
            if ($response->successful()) {
                $path = public_path('uploads/productos/' . $filename);
                file_put_contents($path, $response->body());
                $this->command->info("📸 Descargada: {$filename}");
            }
        } catch (\Exception $e) {
            $this->command->warn("❌ Error descargando {$filename}: " . $e->getMessage());
        }
    }
}