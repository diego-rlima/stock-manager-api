<?php

namespace App\Units\Products\Controllers;

use Exception;
use App\Support\Http\Response;
use App\Support\Http\Parameters;
use App\Support\Http\ApiController;
use App\Units\Products\Resources\ProductResource;
use App\Units\Products\Resources\StockCollection;
use App\Units\Products\Requests\UpdateInventoryRequest;
use App\Domains\Products\Contacts\StockMovementContract;
use App\Domains\Products\Models\Product as ProductModel;
use App\Units\Products\Requests\UpdateMultipleInventoriesRequest;

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

    /**
     * Update the inventory of a product.
     *
     * @param  \App\Units\Products\Requests\UpdateInventoryRequest  $request
     * @param  \App\Domains\Products\Models\Product                 $product
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(UpdateInventoryRequest $request, ProductModel $product)
    {
        try {
            $updated = $this->service->update($product, $request->all());
        } catch (Exception $exception) {
            $updated = false;
        }

        if (!$updated) {
            return $this->response->withInternalServerError();
        }

        return $this->response
            ->withMessage(__('The inventory of this product has been updated.'))
            ->item($product, ProductResource::class);
    }

    /**
     * Update the inventory of multiple products.
     *
     * @param  \App\Units\Products\Requests\UpdateMultipleInventoriesRequest  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function storeMany(UpdateMultipleInventoriesRequest $request)
    {
        try {
            $updated = $this->service->updateMany($request->all());
        } catch (Exception $exception) {
            $updated = false;
        }

        if (!$updated) {
            return $this->response->withInternalServerError();
        }

        return $this->response
            ->withMessage(__('The inventory of these products has been updated.'))
            ->json();
    }
}
