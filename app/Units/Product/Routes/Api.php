<?php

namespace App\Units\Product\Routes;

use App\Support\Http\Route;
use App\Units\Product\Controllers\ProductController;

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
    }
}
