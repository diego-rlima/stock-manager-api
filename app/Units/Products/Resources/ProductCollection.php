<?php

namespace App\Units\Products\Resources;

use App\Support\Http\Resources\BaseResourceCollection;

class ProductCollection extends BaseResourceCollection
{
    /**
     * @var string
     */
    public $collects = ProductResource::class;
}
