<?php

namespace App\Http\Requests\Admin\Products;

use App\Enums\Permissions\ProductEnum;
use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo(ProductEnum::UPDATE_PRODUCTS->value) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        $productId = $product instanceof Product
            ? $product->id
            : $product;

        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('products', 'title')->ignore($productId)],
            'SKU' => ['required', 'string', 'min:1', 'max:35', Rule::unique('products', 'SKU')->ignore($productId)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:1'],
            'discount' => ['nullable', 'numeric', 'min:1', 'max:99'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            'categories.*' => ['required', 'numeric', 'exists:categories,id'],
        ];
    }
}
