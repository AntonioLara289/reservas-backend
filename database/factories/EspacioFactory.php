<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Espacio>
 */
class EspacioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Genera algo como "Sala de Juntas A"
            'name' => $this->faker->unique()->sentence(2), 
            'descripcion' => $this->faker->text(100),
            'capacidad' => $this->faker->numberBetween(5, 50),
            'estatus' => 1, // 1 para activo por defecto
        ];
    
    }
}
