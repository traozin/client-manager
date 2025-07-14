<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'cpf' => [
                'required',
                'string',
                'size:14',
                Rule::unique('clientes', 'cpf')->ignore($this->cliente),
            ],
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|in:masculino,feminino',
            'endereco' => 'nullable|string|max:255',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }
}