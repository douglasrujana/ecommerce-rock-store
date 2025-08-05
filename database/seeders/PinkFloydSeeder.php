<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class PinkFloydSeeder extends Seeder
{
    public function run(): void
    {
        // Primero agrego algunos discos de Pink Floyd
        $nuevosDiscos = [
            [
                'codigo' => 'PF001',
                'nombre' => 'The Dark Side of the Moon - Pink Floyd',
                'descripcion' => 'Obra maestra conceptual. Incluye "Money", "Time" y "Us and Them". Álbum más vendido de todos los tiempos.',
                'precio' => 29.99,
                'imagen' => 'dark-side.jpg'
            ],
            [
                'codigo' => 'PF002', 
                'nombre' => 'The Wall - Pink Floyd',
                'descripcion' => 'Ópera rock épica. Contiene "Another Brick in the Wall", "Comfortably Numb" y "Hey You".',
                'precio' => 32.99,
                'imagen' => 'the-wall.jpg'
            ],
            [
                'codigo' => 'PF003',
                'nombre' => 'Wish You Were Here - Pink Floyd',
                'descripcion' => 'Tributo a Syd Barrett. Incluye "Shine On You Crazy Diamond" y la canción título.',
                'precio' => 28.99,
                'imagen' => 'wish-you-were-here.jpg'
            ]
        ];

        foreach ($nuevosDiscos as $disco) {
            Producto::create($disco);
        }

        // Intentar descargar imágenes de Pink Floyd
        $imagenes = [
            'dark-side.jpg' => 'https://upload.wikimedia.org/wikipedia/en/3/3b/Dark_Side_of_the_Moon.png',
            'the-wall.jpg' => 'https://upload.wikimedia.org/wikipedia/en/1/13/PinkFloydWallCoverOriginalNoText.jpg',
            'wish-you-were-here.jpg' => 'https://upload.wikimedia.org/wikipedia/en/a/a4/Pink_Floyd_-_Wish_You_Were_Here_%281975%29.png'
        ];

        foreach ($imagenes as $archivo => $url) {
            if ($this->descargar($url, $archivo)) {
                $this->command->info("✅ Descargada: {$archivo}");
            }
        }

        $this->command->info('🎸 Pink Floyd agregado a la tienda!');
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
            $this->command->warn("❌ {$filename}");
        }
        return false;
    }
}