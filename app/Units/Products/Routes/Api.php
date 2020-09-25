<?php

namespace App\Units\Products\Routes;

use App\Support\Http\Route;
use App\Units\Products\Controllers\ProductController;

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
