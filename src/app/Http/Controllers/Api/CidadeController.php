<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCidadeRequest;
use App\Http\Requests\UpdateCidadeRequest;
use App\Models\Cidade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class CidadeController extends Controller {
    public function index(Request $request): JsonResponse {
        try {
            $estado = $request->query('estado');

            $query = Cidade::query();

            if ($estado) {
                $query->whereHas('estado', function ($q) use ($estado) {
                    $q->where('sigla', $estado);
                });
            }

            $cidades = $query->orderBy('nome')->get();

            return response()->json([
                'message' => 'Lista de cidades recuperada com sucesso.',
                'data' => $cidades
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar lista de cidades.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Cidade $cidade): JsonResponse {
        try {
            return response()->json([
                'message' => 'Cidade recuperada com sucesso.',
                'data' => $cidade
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar cidade.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreCidadeRequest $request): JsonResponse {
        try {
            $cidade = Cidade::create($request->validated());

            return response()->json([
                'message' => 'Cidade criada com sucesso.',
                'data' => $cidade
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar cidade.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateCidadeRequest $request, Cidade $cidade): JsonResponse {
        try {
            $cidade->update($request->validated());

            return response()->json([
                'message' => 'Cidade atualizada com sucesso.',
                'data' => $cidade
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar cidade.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Cidade $cidade): JsonResponse {
        try {
            $cidade->delete();

            return response()->json([
                'message' => 'Cidade excluída com sucesso.'
            ], 204);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir cidade.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function representantes(Cidade $cidade): JsonResponse {
        try {
            $representantes = $cidade->representantes;

            return response()->json([
                'message' => 'Representantes da cidade recuperados com sucesso.',
                'data' => $representantes
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar representantes da cidade.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
