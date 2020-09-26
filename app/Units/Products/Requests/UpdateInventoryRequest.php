<?php

namespace App\Units\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required|string|max:255',
            'qty' => 'required|integer|min:1|max:99999',
            'type' => 'required|string|in:increase,decrease',
        ];
    }
}
