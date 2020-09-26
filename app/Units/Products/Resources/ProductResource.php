<?php

namespace App\Units\Products\Resources;

use App\Support\Http\Resources\BaseResource;

class ProductResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sku' => $this->sku,
            'in_stock' => (int) $this->in_stock,
            'price' => $this->price,
            'promotional_price' => $this->promotional_price,
            'description' => $this->description,
        ];
    }
}
