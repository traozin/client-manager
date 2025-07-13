<?php

namespace Database\Seeders;

use App\Models\Representante;
use Illuminate\Database\Seeder;

class RepresentanteSeeder extends Seeder {
    public function run(): void {
        Representante::factory()->count(8)->create();
    }
}