<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Database\Seeder;

class CidadeSeeder extends Seeder {
    public function run(): void {
        Estado::all()->each(function ($estado) {
            Cidade::factory()->count(30)->create([
                'estado_id' => $estado->id,
            ]);
        });
    }
}