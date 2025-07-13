<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $fillable = ['nome', 'cidade_id'];

    public function cidade() {
        return $this->belongsTo(Cidade::class);
    }

    public function representantes() {
        return $this->belongsToMany(Representante::class);
    }
}
