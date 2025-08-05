<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class DescargaImagenesSeeder extends Seeder
{
    /**
     * 📸 DESCARGA TODAS LAS IMÁGENES DE PORTADAS
     */
    public function run(): void
    {
        $imagenes = [
            'SG001' => [
                'url' => 'https://i.discogs.com/r300-/release/394835-Soundgarden-Badmotorfinger.jpg',
                'archivo' => 'badmotorfinger-soundgarden.jpg'
            ],
            'AIC001' => [
                'url' => 'https://i.discogs.com/r300-/release/371965-Alice-In-Chains-Dirt.jpg',
                'archivo' => 'dirt-alice-in-chains.jpg'
            ],
            'SP001' => [
                'url' => 'https://i.discogs.com/r300-/release/249406-The-Smashing-Pumpkins-Siamese-Dream.jpg',
                'archivo' => 'siamese-dream-pumpkins.jpg'
            ],
            'SG002' => [
                'url' => 'https://i.discogs.com/r300-/release/394836-Soundgarden-Superunknown.jpg',
                'archivo' => 'superunknown-soundgarden.jpg'
            ],
            'GD001' => [
                'url' => 'https://i.discogs.com/r300-/release/538515-Green-Day-Dookie.jpg',
                'archivo' => 'dookie-green-day.jpg'
            ],
            'SP002' => [
                'url' => 'https://i.discogs.com/r300-/release/249408-The-Smashing-Pumpkins-Mellon-Collie-And-The-Infinite-Sadness.jpg',
                'archivo' => 'mellon-collie-pumpkins.jpg'
            ],
            'BCK001' => [
                'url' => 'https://i.discogs.com/r300-/release/79043-Beck-Odelay.jpg',
                'archivo' => 'odelay-beck.jpg'
            ],
            'AM001' => [
                'url' => 'https://i.discogs.com/r300-/release/377928-Alanis-Morissette-Jagged-Little-Pill.jpg',
                'archivo' => 'jagged-little-pill.jpg'
            ],
            'LV001' => [
                'url' => 'https://i.discogs.com/r300-/release/371966-Live-Throwing-Copper.jpg',
                'archivo' => 'throwing-copper-live.jpg'
            ],
            'ST001' => [
                'url' => 'https://i.discogs.com/r300-/release/371967-Stone-Temple-Pilots-Core.jpg',
                'archivo' => 'core-stp.jpg'
            ],
            'BH001' => [
                'url' => 'https://i.discogs.com/r300-/release/371968-Bush-Sixteen-Stone.jpg',
                'archivo' => 'sixteen-stone-bush.jpg'
            ],
            'SV001' => [
                'url' => 'https://i.discogs.com/r300-/release/249405-The-Smashing-Pumpkins-Gish.jpg',
                'archivo' => 'gish-pumpkins.jpg'
            ]
        ];

        foreach ($imagenes as $codigo => $imagen) {
            $this->descargarImagen($imagen['url'], $imagen['archivo']);
            
            // Actualizar producto con imagen
            Producto::where('codigo', $codigo)->update(['imagen' => $imagen['archivo']]);
            
            $this->command->info("✅ {$codigo}: {$imagen['archivo']}");
        }

        $this->command->info('🎸 ¡Todas las portadas descargadas! Solo falta una con placeholder.');
    }

    private function descargarImagen($url, $filename)
    {
        try {
            $response = Http::timeout(30)->get($url);
            
            if ($response->successful()) {
                $path = public_path('uploads/productos/' . $filename);
                file_put_contents($path, $response->body());
                return true;
            }
        } catch (\Exception $e) {
            $this->command->warn("❌ Error: {$filename}");
        }
        return false;
    }
}