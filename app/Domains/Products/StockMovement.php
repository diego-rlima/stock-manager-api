<?php

namespace App\Domains\Products;

use App\Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use App\Domains\Products\Contacts\StockMovementContract;
use App\Domains\Products\Models\StockMovement as MovementModel;

class StockMovement implements StockMovementContract
{
    /**
     * Get the available qty from a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @return int
     */
    public function getAvailable(Product $product): int
    {
        return $product->stockMovements()->sum('qty');
    }

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
    ): Collection {
        $query = $this->parseQuery($product, $query);

        if ($limit > 0) {
            $query->take($limit);
        }

        return $query->search(null, true)->get();
    }

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
    ): AbstractPaginator {
        return $this->parseQuery($product, $query)
            ->search(null, true)
            ->paginate($limit);
    }

    /**
     * Update the stock of multiple products at once.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $data
     * @return bool
     */
    public function updateMany(Collection $data): bool
    {
        // TODO: Implement updateMany() method.
        return false;
    }

    /**
     * Update the stock of a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  string                                $type
     * @param  int                                   $qty
     * @return bool
     */
    public function update(Product $product, string $type, int $qty): bool
    {
        // TODO: Implement update() method.
        return false;
    }

    /**
     * Get the StockMovement model.
     *
     * @return \App\Domains\Products\Models\StockMovement
     */
    public function getModel(): MovementModel
    {
        return MovementModel::make();
    }

    /**
     * Parse the stock movement query.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  mixed                                 $query
     * @return mixed
     */
    public function parseQuery(Product $product, $query = null)
    {
        $baseQuery = $product->stockMovements();

        if (is_callable($query)) {
            $query = $query($baseQuery);
        }

        return $query ?? $baseQuery;
    }
}
