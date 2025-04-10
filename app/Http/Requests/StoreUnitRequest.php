<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    /**
      * Determine if the user is authorized to make this request.
      */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome'  => ['required', 'string', 'min:5', 'max:200', 'unique:unidade'],
            'sigla' => ['required', 'string', 'min:2', 'max:20', ' unique:unidade'],
        ];
    }
}
