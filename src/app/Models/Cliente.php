<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $fillable = [
        'cpf',
        'nome',
        'data_nascimento',
        'sexo',
        'endereco',
        'estado',
        'cidade_id',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function cidade() {
        return $this->belongsTo(Cidade::class);
    }

    public function representantes() {
        return $this->belongsToMany(Representante::class);
    }
}