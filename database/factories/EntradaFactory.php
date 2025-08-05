<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\entrada;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entrada>
 */
class EntradaFactory extends Factory
{
    //
    protected $model = Entrada::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->lexify(str_repeat('?', 50)), // genera un título
            'tag' => $this->faker->word, // Genear una sóla palabra para el tag
            'imagen' => $this->faker->word, // Genear una sola palabra
            'contenido' => $this->faker->paragraph, // Genear un p+arrafo de contenido
            'user_id' => User::inRandomOrder()->first()->id, // Obtener un user_aleatorio
        ];
    }
}
