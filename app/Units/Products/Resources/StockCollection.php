<?php

namespace App\Units\Products\Resources;

use App\Support\Http\Resources\BaseResourceCollection;

class StockCollection extends BaseResourceCollection
{
    /**
     * @var string
     */
    public $collects = StockResource::class;
}
