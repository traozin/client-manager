<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\CidadeController;

Route::prefix('/v1')->group(function () {
    Route::get('ping', function () {
        return response()->json(['message' => 'pong!']);
    });

    // Cidades
    Route::apiResource('cidades', CidadeController::class);
    Route::get('cidades/{id}/representantes', [CidadeController::class, 'representantes']);

    // Clientes
    Route::apiResource('clientes', ClienteController::class);
    Route::get('clientes/{id}/representantes', [ClienteController::class, 'representantes']);
});
