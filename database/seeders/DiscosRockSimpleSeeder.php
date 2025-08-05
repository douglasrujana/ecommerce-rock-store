<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class DiscosRockSimpleSeeder extends Seeder
{
    /**
     * 🎸 SEEDER SIMPLE DE DISCOS DE ROCK DE LOS 90s
     */
    public function run(): void
    {
        $discos = [
            [
                'codigo' => 'NIR001',
                'nombre' => 'Nevermind - Nirvana',
                'descripcion' => 'Álbum revolucionario que definió el grunge. Incluye "Smells Like Teen Spirit", "Come As You Are" y "Lithium".',
                'precio' => 24.99,
                'imagen' => null
            ],
            [
                'codigo' => 'PJ001',
                'nombre' => 'Ten - Pearl Jam',
                'descripcion' => 'Debut épico de Pearl Jam. Contiene "Alive", "Even Flow" y "Jeremy". Grunge en su máxima expresión.',
                'precio' => 22.99,
                'imagen' => null
            ],
            [
                'codigo' => 'SG001',
                'nombre' => 'Badmotorfinger - Soundgarden',
                'descripcion' => 'Obra maestra del metal alternativo. Incluye "Rusty Cage", "Outshined" y "Jesus Christ Pose".',
                'precio' => 21.99,
                'imagen' => null
            ],
            [
                'codigo' => 'AIC001',
                'nombre' => 'Dirt - Alice in Chains',
                'descripcion' => 'Álbum oscuro y poderoso. Contiene "Them Bones", "Man in the Box" y "Rooster". Grunge melancólico.',
                'precio' => 23.99,
                'imagen' => null
            ],
            [
                'codigo' => 'SP001',
                'nombre' => 'Siamese Dream - Smashing Pumpkins',
                'descripcion' => 'Rock alternativo atmosférico. Incluye "Today", "Cherub Rock" y "Disarm". Producción impecable.',
                'precio' => 25.99,
                'imagen' => null
            ],
            [
                'codigo' => 'SG002',
                'nombre' => 'Superunknown - Soundgarden',
                'descripcion' => 'Obra cumbre de Soundgarden. Contiene "Black Hole Sun", "Spoonman" y "Fell on Black Days".',
                'precio' => 26.99,
                'imagen' => null
            ],
            [
                'codigo' => 'GD001',
                'nombre' => 'Dookie - Green Day',
                'descripcion' => 'Punk rock accesible y pegadizo. Incluye "Longview", "Basket Case" y "When I Come Around".',
                'precio' => 19.99,
                'imagen' => null
            ],
            [
                'codigo' => 'SP002',
                'nombre' => 'Mellon Collie - Smashing Pumpkins',
                'descripcion' => 'Álbum doble épico. Contiene "1979", "Tonight Tonight" y "Bullet with Butterfly Wings".',
                'precio' => 34.99,
                'imagen' => null
            ],
            [
                'codigo' => 'BCK001',
                'nombre' => 'Odelay - Beck',
                'descripcion' => 'Rock experimental y ecléctico. Incluye "Where It\'s At", "Devils Haircut" y "The New Pollution".',
                'precio' => 23.99,
                'imagen' => null
            ],
            [
                'codigo' => 'RH001',
                'nombre' => 'OK Computer - Radiohead',
                'descripcion' => 'Obra maestra del rock alternativo. Contiene "Paranoid Android", "Karma Police" y "No Surprises".',
                'precio' => 27.99,
                'imagen' => null
            ],
            [
                'codigo' => 'AM001',
                'nombre' => 'Jagged Little Pill - Alanis Morissette',
                'descripcion' => 'Rock alternativo con actitud. Incluye "You Oughta Know", "Ironic" y "Hand in My Pocket".',
                'precio' => 21.99,
                'imagen' => null
            ],
            [
                'codigo' => 'LV001',
                'nombre' => 'Throwing Copper - Live',
                'descripcion' => 'Rock alternativo espiritual. Contiene "Lightning Crashes", "Selling the Drama" y "I Alone".',
                'precio' => 20.99,
                'imagen' => null
            ],
            [
                'codigo' => 'ST001',
                'nombre' => 'Core - Stone Temple Pilots',
                'descripcion' => 'Debut poderoso del grunge. Incluye "Interstate Love Song", "Plush" y "Creep".',
                'precio' => 22.99,
                'imagen' => null
            ],
            [
                'codigo' => 'BH001',
                'nombre' => 'Sixteen Stone - Bush',
                'descripcion' => 'Grunge británico. Contiene "Glycerine", "Comedown" y "Machinehead".',
                'precio' => 21.99,
                'imagen' => null
            ],
            [
                'codigo' => 'SV001',
                'nombre' => 'Gish - Smashing Pumpkins',
                'descripcion' => 'Debut atmosférico de los Pumpkins. Incluye "I Am One" y "Siva".',
                'precio' => 23.99,
                'imagen' => null
            ]
        ];

        foreach ($discos as $disco) {
            Producto::create($disco);
        }

        $this->command->info('✅ Se crearon ' . count($discos) . ' discos de rock de los 90s');
    }
}