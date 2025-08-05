<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Producto, Banda, FichaMusical, Cancion};

class DarkSideMuseoSeeder extends Seeder
{
    public function run(): void
    {
        // 🎸 CREAR BANDA PINK FLOYD
        $pinkFloyd = Banda::create([
            'nombre' => 'Pink Floyd',
            'biografia' => 'Banda británica de rock progresivo formada en Londres en 1965. Pioneros en música psicodélica y conceptual, conocidos por sus álbumes conceptuales, letras filosóficas, experimentación sónica y elaborados espectáculos en vivo.',
            'año_formacion' => 1965,
            'miembros' => [
                'David Gilmour' => 'Guitarra, Voz',
                'Roger Waters' => 'Bajo, Voz',
                'Nick Mason' => 'Batería',
                'Richard Wright' => 'Teclados, Voz'
            ],
            'ciudad_origen' => 'Londres',
            'pais_id' => 2, // Reino Unido
            'genero_principal' => 'Rock Progresivo',
            'influencias' => 'Blues, Jazz, Música Clásica, Música Electrónica'
        ]);

        // 🌙 OBTENER DARK SIDE OF THE MOON
        $darkSide = Producto::where('codigo', 'PF001')->first();
        
        if ($darkSide) {
            // 🏛️ CREAR FICHA MUSICAL
            FichaMusical::create([
                'producto_id' => $darkSide->id,
                'banda_id' => $pinkFloyd->id,
                'historia_album' => 'Grabado entre mayo de 1972 y enero de 1973, The Dark Side of the Moon explora temas universales como el conflicto, la avaricia, el tiempo, la muerte y la enfermedad mental. Fue concebido como una suite musical continua.',
                'proceso_grabacion' => 'Grabado en Abbey Road Studios con técnicas innovadoras como el uso de sintetizadores, efectos de sonido y grabaciones de voces habladas. El ingeniero Alan Parsons fue clave en el sonido final.',
                'productor' => 'Pink Floyd',
                'estudio_grabacion' => 'Abbey Road Studios, Londres',
                'impacto_cultural' => 'Permaneció 14 años consecutivos en las listas de Billboard 200. Considerado uno de los álbumes más influyentes de la historia del rock. Sincronizado popularmente con "El Mago de Oz".',
                'premios' => ['Grammy Hall of Fame', 'Rolling Stone Top 500'],
                'ventas_millones' => 45.0,
                'curiosidades' => 'El prisma de la portada representa la luz blanca de la fama dividiéndose en los colores del espectro humano. Las voces habladas fueron grabadas entrevistando al personal del estudio.',
                'equipos_usados' => [
                    'Guitarras' => 'Fender Stratocaster, Gibson Les Paul',
                    'Amplificadores' => 'Hiwatt, Marshall',
                    'Sintetizadores' => 'VCS3, Minimoog',
                    'Efectos' => 'Binson Echorec, Big Muff'
                ]
            ]);

            // 🎵 CREAR CANCIONES CON ACORDES
            $canciones = [
                [
                    'titulo' => 'Speak to Me',
                    'duracion' => '1:30',
                    'track_number' => 1,
                    'letra_original' => '[Instrumental with sound effects]',
                    'nivel_ingles' => 'A1',
                    'analisis_musical' => 'Introducción instrumental con efectos de sonido. Acordes: Em - Am - Em'
                ],
                [
                    'titulo' => 'Breathe (In the Air)',
                    'duracion' => '2:43',
                    'track_number' => 2,
                    'letra_original' => "Breathe, breathe in the air\nDon't be afraid to care\nLeave but don't leave me\nLook around and choose your own ground",
                    'letra_traducida' => "Respira, respira el aire\nNo tengas miedo de importarte\nVete pero no me dejes\nMira alrededor y elige tu propio terreno",
                    'nivel_ingles' => 'B1',
                    'vocabulario_clave' => ['breathe', 'afraid', 'care', 'ground'],
                    'analisis_musical' => 'Canción en Em con progresión: Em - A - Em - A - C - Bm - Em. Guitarra fingerpicking con slide guitar.'
                ],
                [
                    'titulo' => 'Time',
                    'duracion' => '6:53',
                    'track_number' => 4,
                    'letra_original' => "Ticking away the moments that make up a dull day\nYou fritter and waste the hours in an offhand way",
                    'letra_traducida' => "Marcando los momentos que forman un día aburrido\nDerrochas y desperdicias las horas de manera descuidada",
                    'nivel_ingles' => 'B2',
                    'vocabulario_clave' => ['ticking', 'moments', 'fritter', 'waste', 'offhand'],
                    'analisis_musical' => 'Comienza en F#m, progresión principal: F#m - A - E - F#m. Solo de guitarra icónico en pentatónica menor.'
                ],
                [
                    'titulo' => 'Money',
                    'duracion' => '6:23',
                    'track_number' => 6,
                    'letra_original' => "Money, get away\nGet a good job with good pay and you're okay\nMoney, it's a gas",
                    'letra_traducida' => "Dinero, aléjate\nConsigue un buen trabajo con buen sueldo y estarás bien\nDinero, es genial",
                    'nivel_ingles' => 'A2',
                    'vocabulario_clave' => ['money', 'job', 'pay', 'gas'],
                    'analisis_musical' => 'Famoso riff en 7/4: Bm - F#m - Em - Bm. Bajo prominente con efectos de caja registradora.'
                ]
            ];

            foreach ($canciones as $cancion) {
                Cancion::create(array_merge($cancion, ['producto_id' => $darkSide->id]));
            }
        }

        $this->command->info('🌙 Dark Side of the Moon - Ficha de museo creada!');
    }
}