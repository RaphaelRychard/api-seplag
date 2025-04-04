<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermanentServantRequest extends FormRequest
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
            'nome'            => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date'],
            'sexo'            => ['required', 'string'],
            'mae'             => ['required', 'string'],
            'pai'             => ['required', 'string'],
            'se_matricula'    => [
                'required',
                'string',
                'max:255',
                'unique:servidor_efetivo,se_matricula',
            ],
        ];
    }
}
