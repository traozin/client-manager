<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCidadeRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'nome' => 'required|string|max:255|unique:cidades,nome,' . $this->route('cidade'),
        ];
    }
}
