<?php

namespace App\Units\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultipleInventoriesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products' => 'required|array|min:1|max:30',
            'products.*.id' => 'required|integer|distinct|exists:products',
            'products.*.qty' => 'required|integer|min:1|max:99999',
            'description' => 'required|string|max:255',
            'type' => 'required|string|in:increase,decrease',
        ];
    }
}
