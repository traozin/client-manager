<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ViewClienteController extends Controller {
    public function create() {
        return view('clientes.create');
    }

    public function index() {
        return view('web.index');
    }
}
