<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
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
            'pes_nome'            => ['required' , 'min:3' , 'max:200'],
            'pes_data_nascimento' => ['required' , 'date'],
            'pes_sexo'            => ['required' , 'min:3' , 'max:200'],
            'pes_mae'             => ['required' , 'min:3' , 'max:200'],
            'pes_pai'             => ['required' , 'min:3' , 'max:200'],
        ];
    }
}
