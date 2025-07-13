<?php

namespace Database\Factories;

use App\Models\Cidade;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory {
    protected $model = Cliente::class;

    public function definition(): array {
        return [
            'nome' => $this->faker->name,
            'cidade_id' => Cidade::inRandomOrder()->first()?->id ?? Cidade::factory(),
        ];
    }
}
