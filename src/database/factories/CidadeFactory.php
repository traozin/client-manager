<?php

namespace Database\Factories;

use App\Models\Cidade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cidade>
 */
class CidadeFactory extends Factory {
    protected $model = Cidade::class;

    public function definition(): array {
        return [
            'nome' => $this->faker->city,
        ];
    }
}