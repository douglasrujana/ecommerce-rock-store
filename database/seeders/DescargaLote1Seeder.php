<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class DescargaLote1Seeder extends Seeder
{
    public function run(): void
    {
        $imagenes = [
            'SG001' => [
                'url' => 'https://upload.wikimedia.org/wikipedia/en/a/a9/Soundgarden_-_Badmotorfinger.jpg',
                'archivo' => 'badmotorfinger.jpg'
            ],
            'AIC001' => [
                'url' => 'https://upload.wikimedia.org/wikipedia/en/9/95/AliceInChainsDirt.jpg', 
                'archivo' => 'dirt.jpg'
            ],
            'SP001' => [
                'url' => 'https://upload.wikimedia.org/wikipedia/en/9/9f/Siamese_Dream.jpg',
                'archivo' => 'siamese.jpg'
            ]
        ];

        foreach ($imagenes as $codigo => $data) {
            if ($this->descargar($data['url'], $data['archivo'])) {
                Producto::where('codigo', $codigo)->update(['imagen' => $data['archivo']]);
                $this->command->info("✅ {$codigo}: {$data['archivo']}");
            }
        }
    }

    private function descargar($url, $filename)
    {
        try {
            $response = Http::timeout(15)->get($url);
            if ($response->successful()) {
                file_put_contents(public_path('uploads/productos/' . $filename), $response->body());
                return true;
            }
        } catch (\Exception $e) {
            $this->command->warn("❌ {$filename}");
        }
        return false;
    }
}