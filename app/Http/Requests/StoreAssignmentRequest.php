<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssignmentRequest extends FormRequest
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
            'pes_id' => [
                'required',
                'string',
                'exists:pessoa,id',
                Rule::unique('lotacao')->whereNull('data_remocao'),
            ],
            'unid_id'      => ['required', 'string', 'exists:unidade,id'],
            'data_lotacao' => ['required', 'date'],
            'data_remocao' => ['nullable', 'date', 'after_or_equal:data_lotacao'],
            'portaria'     => ['required', 'string', 'min:3', 'max:100'],
        ];
    }
}
