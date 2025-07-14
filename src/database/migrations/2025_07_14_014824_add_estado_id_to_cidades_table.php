<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoIdToCidadesTable extends Migration {
    public function up() {
        Schema::table('cidades', function (Blueprint $table) {
            $table->foreignId('estado_id')->after('nome')->constrained()->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('cidades', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropColumn('estado_id');
        });
    }
}