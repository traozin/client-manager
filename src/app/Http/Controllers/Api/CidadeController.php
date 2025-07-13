<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCidadeRequest;
use App\Http\Requests\UpdateCidadeRequest;
use App\Models\Cidade;

class CidadeController extends Controller {
    public function index() {
        $cidades = Cidade::all();

        return response()->json([
            'message' => 'Lista de cidades recuperada com sucesso.',
            'data' => $cidades
        ]);
    }

    public function show(Cidade $cidade) {
        return response()->json([
            'message' => 'Cidade recuperada com sucesso.',
            'data' => $cidade
        ]);
    }

    public function store(StoreCidadeRequest $request) {
        $cidade = Cidade::create($request->validated());

        return response()->json([
            'message' => 'Cidade criada com sucesso.',
            'data' => $cidade
        ], 201);
    }

    public function update(UpdateCidadeRequest $request, Cidade $cidade) {
        $cidade->update($request->validated());

        return response()->json([
            'message' => 'Cidade atualizada com sucesso.',
            'data' => $cidade
        ]);
    }

    public function destroy(Cidade $cidade) {
        $cidade->delete();

        return response()->json([
            'message' => 'Cidade excluída com sucesso.'
        ], 204);
    }

    public function representantes(Cidade $cidade) {
        $representantes = $cidade->representantes;

        return response()->json([
            'message' => 'Representantes da cidade recuperados com sucesso.',
            'data' => $representantes
        ]);
    }
}
