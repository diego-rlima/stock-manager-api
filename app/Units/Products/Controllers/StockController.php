<?php

namespace App\Units\Products\Controllers;

use App\Support\Http\Response;
use App\Support\Http\Parameters;
use App\Support\Http\ApiController;
use App\Units\Products\Resources\StockCollection;
use App\Domains\Products\Contacts\StockMovementContract;
use App\Domains\Products\Models\Product as ProductModel;

class StockController extends ApiController
{
    /**
     * @var \App\Domains\Products\Contacts\StockMovementContract
     */
    protected StockMovementContract $service;

    /**
     * StockController constructor.
     *
     * @param  \App\Support\Http\Response                      $response
     * @param  \App\Support\Http\Parameters                    $parameters
     * @param  \App\Domains\Products\Contacts\StockMovementContract  $service
     */
    public function __construct(
        Response $response,
        Parameters $parameters,
        StockMovementContract $service
    ) {
        parent::__construct($response, $parameters);
        $this->service = $service;
    }

    /**
     * Display a listing of stock movement from a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ProductModel $product)
    {
        $products = $this->service->paginateMovementHistoric(
            $product,
            $this->parameters->limit(10),
            function ($query) {
                return $query->latest('id');
            }
        );

        return $this->response->collection($products, StockCollection::class);
    }
}
