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
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'nome' => $this->faker->name(),
            'data_nascimento' => $this->faker->date('Y-m-d', '-18 years'),
            'sexo' => $this->faker->randomElement(['masculino', 'feminino']),
            'endereco' => $this->faker->address(),
            'cidade_id' => Cidade::inRandomOrder()->first()->id,
        ];
    }
}