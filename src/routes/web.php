<?php

use App\Http\Controllers\Web\ViewClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ViewClienteController::class, 'index']);
Route::get('/clientes/criar', [ViewClienteController::class, 'create'])->name('clientes.create');