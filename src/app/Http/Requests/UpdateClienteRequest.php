<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }


    public function rules(): array {
        return [
            'nome' => 'sometimes|required|string|max:255',
            'cidade_id' => 'sometimes|required|exists:cidades,id',
        ];
    }
}
