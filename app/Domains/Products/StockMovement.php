<?php

namespace App\Domains\Products;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use App\Domains\Products\Contacts\ProductContract;
use App\Domains\Products\Models\Product as ProductModel;
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
    public function getAvailable(ProductModel $product): int
    {
        return $product->stockMovements()
            ->get(['qty', 'type'])
            ->reduce(function ($sum, $item) {
                $qty = $item['type'] == 'increase'
                    ? $item['qty']
                    : $item['qty'] * (-1);

                return $sum + $qty;
            }, 0);
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
        ProductModel $product,
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
        ProductModel $product,
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
     * @param  array  $data
     * @return bool
     * @throws \Throwable
     */
    public function updateMany(array $data): bool
    {
        $type = $data['type'] ?? null;
        $description = $data['description'] ?? null;
        $products = collect($data['products'] ?? []);
        $loadedProducts = $this->loadProductsToUpdate($data['products'] ?? []);

        $updated = $products->map(function (array $data) use (
            $loadedProducts,
            $type,
            $description
        ) {
            $product = $loadedProducts->firstWhere('id', $data['id']);

            if (!$product) {
                return false;
            }

            return $this->simpleUpdate($product, [
                'qty' => $data['qty'],
                'type' => $type,
                'description' => $description,
            ]);
        });

        if ($updated->filter()->count() != $products->count()) {
            DB::rollBack();
            return false;
        }

        DB::commit();

        return true;
    }

    /**
     * Update the stock of a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  array                                 $data
     * @return bool
     * @throws \Throwable
     */
    public function update(ProductModel $product, array $data): bool
    {
        DB::beginTransaction();

        $updated = $this->simpleUpdate($product, $data);

        if (!$updated) {
            DB::rollBack();
            return false;
        }

        DB::commit();

        return true;
    }

    /**
     * Update the stock of a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  array                                 $data
     * @return bool
     */
    protected function simpleUpdate(ProductModel $product, array $data): bool
    {
        $data = $this->formatData($data);
        $updated = $this->parseQuery($product)->create($data);

        if ($updated) {
            $updated = $this->updateProduct(
                $product,
                $updated->qty,
                $updated->type
            );
        }

        return (bool) $updated;
    }

    /**
     * Load multiple products to update the inventory.
     *
     * @param  array  $products
     * @return false|\Illuminate\Database\Eloquent\Collection
     */
    protected function loadProductsToUpdate(array $products)
    {
        $productService = resolve(ProductContract::class);
        $ids = collect($products)->pluck('id')->filter();

        if ($ids->isEmpty()) {
            return false;
        }

        return $productService
            ->listProducts(0, null, function ($query) use ($ids) {
                $query->whereIn('id', $ids->toArray());
            });
    }

    /**
     * Update the product `in_stock` column.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  int                                   $qty
     * @param  string                                $type
     * @return bool
     */
    protected function updateProduct(
        ProductModel $product,
        int $qty,
        string $type
    ): bool {
        if ($type == 'decrease') {
            $qty *= -1;
        }

        return $product->forceFill([
            'in_stock' => $product->in_stock + $qty
        ])
            ->save();
    }

    /**
     * Format the register info.
     *
     * @param  array  $data
     * @return array
     */
    protected function formatData(array $data): array
    {
        $qty = abs($data['qty'] ?? 1);

        return [
            'qty' => $qty,
            'description' => $data['description'] ?? 'Inventory update',
            'type' => $data['type'] ?? 'increase',
        ];
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
    public function parseQuery(ProductModel $product, $query = null)
    {
        $baseQuery = $product->stockMovements();

        if (is_callable($query)) {
            $query = $query($baseQuery);
        }

        return $query ?? $baseQuery;
    }
}
