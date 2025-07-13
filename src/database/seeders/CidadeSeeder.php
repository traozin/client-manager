<?php

namespace Database\Seeders;

use App\Models\Cidade;
use Illuminate\Database\Seeder;

class CidadeSeeder extends Seeder {
    public function run(): void {
        Cidade::factory()->count(5)->create();
    }
}