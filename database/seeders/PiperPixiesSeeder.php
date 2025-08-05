<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class PiperPixiesSeeder extends Seeder
{
    public function run(): void
    {
        $discos = [
            [
                'codigo' => 'PF004',
                'nombre' => 'The Piper at the Gates of Dawn - Pink Floyd',
                'descripcion' => 'Debut psicodélico de Pink Floyd con Syd Barrett. Incluye "Astronomy Domine", "Interstellar Overdrive" y "See Emily Play".',
                'precio' => 35.99,
                'imagen' => 'piper-gates.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/3/3c/Pink_Floyd_-_The_Piper_at_the_Gates_of_Dawn.jpg'
            ],
            [
                'codigo' => 'PX001',
                'nombre' => 'Doolittle - Pixies',
                'descripcion' => 'Obra maestra del rock alternativo. Contiene "Debaser", "Here Comes Your Man" y "Monkey Gone to Heaven".',
                'precio' => 24.99,
                'imagen' => 'doolittle.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/6/6e/Pixies-Doolittle.jpg'
            ],
            [
                'codigo' => 'PX002',
                'nombre' => 'Surfer Rosa - Pixies',
                'descripcion' => 'Debut brutal de los Pixies. Incluye "Where Is My Mind?", "Gigantic" y "Bone Machine".',
                'precio' => 23.99,
                'imagen' => 'surfer-rosa.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/4/4c/Pixies-Surfer_Rosa.jpg'
            ],
            [
                'codigo' => 'PX003',
                'nombre' => 'Bossanova - Pixies',
                'descripcion' => 'Tercer álbum de los Pixies. Contiene "Velouria", "Dig for Fire" y "Is She Weird".',
                'precio' => 22.99,
                'imagen' => 'bossanova.jpg',
                'url' => 'https://upload.wikimedia.org/wikipedia/en/a/a9/Pixies-Bossanova.jpg'
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
                $this->command->warn("❌ {$disco['codigo']}: Sin imagen");
            }
        }

        $this->command->info('🎸 Piper at the Gates of Dawn y Pixies agregados!');
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