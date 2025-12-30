<?php

namespace App\Http\Requests\Admin\Categories;

use App\Enums\Permissions\CategoryEnum;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo(CategoryEnum::UPDATE_CATEGORY->value) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');
        $categoryId = $category instanceof Category
            ? $category->getKey()
            : $category;

        return [
            'title' => ['required', 'string', 'min:2', 'max:255', Rule::unique('categories', 'title')->ignore($categoryId)],
            'parent_id' => ['nullable', 'numeric', 'exists:categories,id'],
        ];
    }
}
