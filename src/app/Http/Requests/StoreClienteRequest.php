<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'nome' => 'required|string|max:255',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }
}
