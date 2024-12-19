<?php

namespace App\Http\Requests\Product;

use App\Enum\ProductStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'slug' => ['sometimes', 'string', Rule::unique('products', 'slug')->ignore($this->route('id'))],
            'sku' => ['sometimes', 'string', Rule::unique('products', 'sku')->ignore($this->route('id'))],
            'sub_label' => ['sometimes', 'string'],
            'status' => ['sometimes', 'string', Rule::in(array_column(ProductStatusEnum::cases(), 'value'))],
        ];
    }
}
