<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller {

    public function index(Request $request) {
        $query = Cliente::with('cidade');

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        $clientes = $query->paginate(10);

        return response()->json([
            'message' => 'Lista de clientes recuperada com sucesso.',
            'data' => $clientes
        ]);
    }

    public function show(Cliente $cliente) {
        $cliente->load('cidade');

        return response()->json([
            'message' => 'Cliente recuperado com sucesso.',
            'data' => $cliente
        ]);
    }

    public function store(StoreClienteRequest $request) {
        $cliente = Cliente::create($request->validated());

        return response()->json([
            'message' => 'Cliente criado com sucesso.',
            'data' => $cliente
        ], 201);
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente) {
        $cliente->update($request->validated());

        return response()->json([
            'message' => 'Cliente atualizado com sucesso.',
            'data' => $cliente
        ]);
    }

    public function destroy(Cliente $cliente) {
        $cliente->delete();

        return response()->json([
            'message' => 'Cliente excluído com sucesso.'
        ], 204);
    }

    public function representantes(Cliente $cliente) {
        $representantes = $cliente->representantes;

        return response()->json([
            'message' => 'Representantes do cliente recuperados com sucesso.',
            'data' => $representantes
        ]);
    }
}
