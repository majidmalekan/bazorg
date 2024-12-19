<?php

namespace App\Http\Requests\Product;

use App\Enum\ProductStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'=>['required','string'],
            'description'=>['required','string'],
            'slug'=>['required','string',Rule::unique('products','slug')],
            'sku'=>['required','string',Rule::unique('products','sku')],
            'sub_label'=>['required','string'],
            'status'=>['required','string',Rule::in(array_column(ProductStatusEnum::cases(), 'value'))],
        ];
    }
}
