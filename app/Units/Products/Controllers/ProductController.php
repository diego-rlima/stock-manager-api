<?php

namespace App\Units\Products\Controllers;

use App\Support\Http\Response;
use App\Support\Http\Parameters;
use App\Support\Http\ApiController;
use App\Units\Products\Resources\ProductResource;
use App\Domains\Products\Contacts\ProductContract;
use App\Units\Products\Resources\ProductCollection;
use App\Units\Products\Requests\CreateProductRequest;
use App\Units\Products\Requests\UpdateProductRequest;
use App\Domains\Products\Models\Product as ProductModel;

class ProductController extends ApiController
{
    /**
     * @var \App\Domains\Products\Contacts\ProductContract
     */
    protected ProductContract $service;

    /**
     * ProductController constructor.
     *
     * @param  \App\Support\Http\Response                      $response
     * @param  \App\Support\Http\Parameters                    $parameters
     * @param  \App\Domains\Products\Contacts\ProductContract  $service
     */
    public function __construct(
        Response $response,
        Parameters $parameters,
        ProductContract $service
    ) {
        parent::__construct($response, $parameters);
        $this->service = $service;
    }

    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = $this->service->paginateProducts(
            $this->parameters->limit(10)
        );

        return $this->response->collection($products, ProductCollection::class);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ProductModel $product)
    {
        return $this->response->item($product, ProductResource::class);
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Units\Products\Requests\CreateProductRequest  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(CreateProductRequest $request)
    {
        $product = $this->service->create($request->all());

        if (!$product) {
            return $this->response->withInternalServerError();
        }

        return $this->response
            ->withMessage(__('The product was successfully added.'))
            ->withCreated($product, ProductResource::class);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \App\Units\Products\Requests\UpdateProductRequest  $request
     * @param  \App\Domains\Products\Models\Product               $product
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(UpdateProductRequest $request, ProductModel $product)
    {
        $updated = $this->service->update($product, $request->all());

        if (!$updated) {
            return $this->response->withInternalServerError();
        }

        return $this->response
            ->withMessage(__('The product has been successfully updated.'))
            ->item($product, ProductResource::class);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductModel $product)
    {
        if (!$this->service->delete($product)) {
            return $this->response->withInternalServerError();
        }

        return $this->response->withNoContent();
    }
}
