<?php

namespace App\Units\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $product = $this->route()->parameter('product');

        return [
            'title' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:products,sku' . ($product ? ",$product->id" : ''),
            'description' => 'nullable|string|max:25000',
            'price' => 'nullable|numeric|max:9999999',
            'promotional_price' => 'nullable|numeric|max:9999999',
        ];
    }
}
