<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'cpf' => 'required|string|size:14|unique:clientes,cpf',
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|in:masculino,feminino',
            'endereco' => 'nullable|string|max:255',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }
}