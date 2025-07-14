<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller {

    public function index(Request $request) {
        try {
            $query = Cliente::query()
                ->select('clientes.*')
                ->join('cidades', 'clientes.cidade_id', '=', 'cidades.id')
                ->join('estados', 'cidades.estado_id', '=', 'estados.id')
                ->with('cidade.estado');

            if ($request->filled('cpf')) {
                $query->where('clientes.cpf', 'like', '%' . $request->cpf . '%');
            }

            if ($request->filled('nome')) {
                $query->where('clientes.nome', 'like', '%' . $request->nome . '%');
            }

            if ($request->filled('data_nascimento')) {
                $query->whereDate('clientes.data_nascimento', $request->data_nascimento);
            }

            if ($request->filled('sexo')) {
                $query->where('clientes.sexo', $request->sexo);
            }

            if ($request->filled('endereco')) {
                $query->where('clientes.endereco', 'like', '%' . $request->endereco . '%');
            }

            if ($request->filled('cidade_id')) {
                $query->where('clientes.cidade_id', $request->cidade_id);
            }

            if ($request->filled('estado')) {
                $query->where('estados.sigla', $request->estado);
            }

            $clientes = $query->paginate(10);

            return response()->json([
                'message' => 'Lista de clientes recuperada com sucesso.',
                'data' => $clientes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar lista de clientes.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Cliente $cliente) {
        try {
            $cliente->load('cidade');

            return response()->json([
                'message' => 'Cliente recuperado com sucesso.',
                'data' => $cliente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar cliente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreClienteRequest $request) {
        try {
            $cliente = Cliente::create($request->validated());

            return response()->json([
                'message' => 'Cliente criado com sucesso.',
                'data' => $cliente
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar cliente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente) {
        try {
            $cliente->update($request->validated());

            return response()->json([
                'message' => 'Cliente atualizado com sucesso.',
                'data' => $cliente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar cliente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Cliente $cliente) {
        try {
            $cliente->delete();

            return response()->json([
                'message' => 'Cliente excluído com sucesso.'
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir cliente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function representantes(Cliente $cliente) {
        try {
            $representantes = $cliente->representantes;

            return response()->json([
                'message' => 'Representantes do cliente recuperados com sucesso.',
                'data' => $representantes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar representantes do cliente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
