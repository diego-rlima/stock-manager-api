<?php

namespace App\Domains\Products\Contacts;

use App\Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use App\Domains\Products\Models\StockMovement;
use App\Support\Domains\Contracts\ServiceContract;

interface StockMovementContract extends ServiceContract
{
    /**
     * Get the available qty from a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @return int
     */
    public function getAvailable(Product $product): int;

    /**
     * Get the historic of stock movement from a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  int                                   $limit
     * @param  mixed                                 $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listMovementHistoric(
        Product $product,
        int $limit,
        $query = null
    ): Collection;

    /**
     * Get the paginated historic of stock movement from a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  int                                   $limit
     * @param  mixed                                 $query
     * @return \Illuminate\Pagination\AbstractPaginator
     */
    public function paginateMovementHistoric(
        Product $product,
        int $limit,
        $query = null
    ): AbstractPaginator;

    /**
     * Update the stock of multiple products at once.
     *
     * @param  array  $data
     * @return bool
     */
    public function updateMany(array $data): bool;

    /**
     * Update the stock of a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  array                                 $data
     * @return bool
     */
    public function update(Product $product, array $data): bool;

    /**
     * Get the StockMovement model.
     *
     * @return \App\Domains\Products\Models\StockMovement
     */
    public function getModel(): StockMovement;

    /**
     * Parse the stock movement query.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  mixed                                 $query
     * @return mixed
     */
    public function parseQuery(Product $product, $query = null);
}
