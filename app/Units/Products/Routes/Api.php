<?php

namespace App\Units\Products\Routes;

use App\Support\Http\Route;
use App\Units\Products\Controllers\ProductController;
use App\Units\Products\Controllers\StockController;

class Api extends Route
{
    /**
     * Define the routes.
     *
     * @return void
     */
    public function routes(): void
    {
        $this->router->apiResource('products', ProductController::class);
        $this->router->resource('products.stock', StockController::class)
            ->only(['index', 'store']);
    }
}
