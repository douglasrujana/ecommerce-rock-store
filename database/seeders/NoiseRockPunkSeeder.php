<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class NoiseRockPunkSeeder extends Seeder
{
    public function run(): void
    {
        $discos = [
            [
                'codigo' => 'SY001',
                'nombre' => 'Daydream Nation - Sonic Youth',
                'descripcion' => 'Obra maestra del noise rock. Incluye "Teen Age Riot", "Silver Rocket" y "Candle".',
                'precio' => 29.99,
                'imagen' => 'daydream-nation.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/3/3f/Daydream_Nation.jpg'
            ],
            [
                'codigo' => 'SY002',
                'nombre' => 'Goo - Sonic Youth',
                'descripcion' => 'Álbum de transición al mainstream. Contiene "Kool Thing", "Dirty Boots" y "Tunic".',
                'precio' => 26.99,
                'imagen' => 'goo.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/a/a4/Sonic_Youth_-_Goo.jpg'
            ],
            [
                'codigo' => 'DJ001',
                'nombre' => 'You\'re Living All Over Me - Dinosaur Jr.',
                'descripcion' => 'Clásico del indie rock. Incluye "Little Fury Things", "Kracked" y "Sludgefeast".',
                'precio' => 25.99,
                'imagen' => 'living-all-over.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/9/9a/Dinosaur_Jr._-_You%27re_Living_All_Over_Me.jpg'
            ],
            [
                'codigo' => 'RA001',
                'nombre' => 'Ramones - Ramones',
                'descripcion' => 'Debut que inventó el punk rock. Contiene "Blitzkrieg Bop", "Judy Is a Punk" y "Now I Wanna Sniff Some Glue".',
                'precio' => 24.99,
                'imagen' => 'ramones-debut.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/a/a6/Ramones_-_Ramones_cover.jpg'
            ],
            [
                'codigo' => 'RA002',
                'nombre' => 'Rocket to Russia - Ramones',
                'descripcion' => 'Tercer álbum de los Ramones. Incluye "Sheena Is a Punk Rocker", "Pet Sematary" y "Rockaway Beach".',
                'precio' => 23.99,
                'imagen' => 'rocket-russia.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/3/3c/Ramones_-_Rocket_to_Russia_cover.jpg'
            ],
            [
                'codigo' => 'TS001',
                'nombre' => 'The Queen Is Dead - The Smiths',
                'descripcion' => 'Obra maestra del indie pop. Contiene "There Is a Light That Never Goes Out", "Bigmouth Strikes Again" y la canción título.',
                'precio' => 28.99,
                'imagen' => 'queen-is-dead.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/3/33/The_Smiths-The_Queen_Is_Dead.jpg'
            ],
            [
                'codigo' => 'TS002',
                'nombre' => 'Meat Is Murder - The Smiths',
                'descripcion' => 'Segundo álbum de The Smiths. Incluye "How Soon Is Now?", "That Joke Isn\'t Funny Anymore" y la canción título.',
                'precio' => 27.99,
                'imagen' => 'meat-is-murder.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/2/2c/The_Smiths_Meat_Is_Murder.jpg'
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

        $this->command->info('🎸 Noise rock, punk e indie agregados!');
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