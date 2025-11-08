<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && auth()->user()->is_admin; }

    public function rules(): array {
        return [
            'name'        => 'required|string|max:120',
            'slug'        => ['nullable','string','max:140', Rule::unique('products','slug')->ignore($this->product->id)],
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer|min:0',
            'sale_price'  => 'nullable|integer|min:0|lte:price',
            'stock'       => 'required|integer|min:0',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|max:4096',
            'description' => 'nullable|string|max:3000',
        ];
    }
}
