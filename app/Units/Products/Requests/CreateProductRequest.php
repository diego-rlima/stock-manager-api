<?php

namespace App\Units\Products\Requests;

class CreateProductRequest extends UpdateProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules() + [
            'qty' => 'nullable|integer|min:1|max:50000',
        ];
    }
}
