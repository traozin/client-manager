<?php

namespace Database\Factories;

use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cidade>
 */
class CidadeFactory extends Factory {
    protected $model = Cidade::class;

    public function definition(): array {
        $estado = Estado::inRandomOrder()->first();

        return [
            'nome' => $this->faker->city,
            'estado_id' => $estado ? $estado->id : null, // garante relacionamento válido
        ];
    }
}