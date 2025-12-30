<?php

namespace App\Http\Requests\Admin\Categories;

use App\Enums\Permissions\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo(CategoryEnum::CREATE_CATEGORY->value) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255', 'unique:categories'],
            'parent_id' => ['nullable', 'numeric', 'exists:categories,id'],
        ];
    }
}
