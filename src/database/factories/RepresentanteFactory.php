<?php

namespace Database\Factories;

use App\Models\Cidade;
use App\Models\Representante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Representante>
 */
class RepresentanteFactory extends Factory {
    protected $model = Representante::class;

    public function definition(): array {
        return [
            'nome' => $this->faker->name,
            'cidade_id' => Cidade::inRandomOrder()->first()?->id ?? Cidade::factory(),
        ];
    }
}