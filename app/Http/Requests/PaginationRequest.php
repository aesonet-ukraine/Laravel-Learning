<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trashed' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'page' => ['nullable', 'integer', 'min:1'],
            'filters.*' => ['nullable', 'array', 'in_array_keys:key,operator,value'],
            'sort.*' => ['nullable', 'array', 'in_array_keys:key,direction'],
            'sort.*.key' => ['required_with:sort.*.direction', 'string'],
            'sort.*.direction' => ['required_with:sort.*.key', 'in:asc,desc'],
        ];
    }
}
