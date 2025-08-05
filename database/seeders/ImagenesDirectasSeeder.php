<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class ImagenesDirectasSeeder extends Seeder
{
    public function run(): void
    {
        $imagenes = [
            'SG001' => 'https://upload.wikimedia.org/wikipedia/en/a/a9/Soundgarden_-_Badmotorfinger.jpg',
            'AIC001' => 'https://upload.wikimedia.org/wikipedia/en/9/95/AliceInChainsDirt.jpg',
            'SP001' => 'https://upload.wikimedia.org/wikipedia/en/9/9f/Siamese_Dream.jpg',
            'SG002' => 'https://upload.wikimedia.org/wikipedia/en/3/3a/Soundgarden_-_Superunknown.jpg',
            'GD001' => 'https://upload.wikimedia.org/wikipedia/en/2/22/Dookie.jpg',
            'SP002' => 'https://upload.wikimedia.org/wikipedia/en/1/1d/SmashingPumpkins-MellonCollie.jpg',
            'BCK001' => 'https://upload.wikimedia.org/wikipedia/en/4/42/Beck_-_Odelay.png',
            'AM001' => 'https://upload.wikimedia.org/wikipedia/en/3/3c/Alanis_Morissette_-_Jagged_Little_Pill.png',
            'LV001' => 'https://upload.wikimedia.org/wikipedia/en/4/4f/Live_Throwing_Copper.jpg',
            'ST001' => 'https://upload.wikimedia.org/wikipedia/en/5/53/STPCore.jpg',
            'BH001' => 'https://upload.wikimedia.org/wikipedia/en/5/50/Bush_Sixteen_Stone.jpg'
        ];

        foreach ($imagenes as $codigo => $url) {
            $extension = pathinfo($url, PATHINFO_EXTENSION);
            $filename = strtolower($codigo) . '-cover.' . $extension;
            
            if ($this->descargarImagen($url, $filename)) {
                Producto::where('codigo', $codigo)->update(['imagen' => $filename]);
                $this->command->info("✅ {$codigo}: {$filename}");
            }
        }
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