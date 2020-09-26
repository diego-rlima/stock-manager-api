<?php

namespace App\Domains\Products;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use App\Domains\Products\Contacts\ProductContract;
use App\Domains\Products\Models\Product as ProductModel;
use App\Domains\Products\Contacts\StockMovementContract;

class Product implements ProductContract
{
    /**
     * Find a product by its ID.
     *
     * @param  int   $id
     * @param  bool  $throwable
     * @return \App\Domains\Products\Models\Product|null
     */
    public function findById(int $id, bool $throwable = true): ?ProductModel
    {
        return $this->find((string) $id, 'id', $throwable);
    }

    /**
     * Find a product by its SKU.
     *
     * @param  string  $sku
     * @param  bool    $throwable
     * @return \App\Domains\Products\Models\Product|null
     */
    public function findBySku(string $sku, bool $throwable = true): ?ProductModel
    {
        return $this->find($sku, 'sku', $throwable);
    }

    /**
     * Try find a product by a given value and column.
     *
     * @param  string  $value
     * @param  string  $column
     * @param  bool    $throwable
     * @return \App\Domains\Products\Models\Product|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function find(string $value, string $column, bool $throwable = true): ?ProductModel
    {
        $query = $this->parseQuery()->where($column, $value);

        return $throwable ? $query->firstOrFail() : $query->first();
    }

    /**
     * Get a list of products.
     *
     * @param  int          $limit
     * @param  string|null  $searchTerm
     * @param  mixed        $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listProducts(
        int $limit,
        string $searchTerm = null,
        $query = null
    ): Collection {
        $query = $this->parseQuery($query);

        if ($limit > 0) {
            $query->take($limit);
        }

        return $query->search($searchTerm, true)->get();
    }

    /**
     * Get a paginated list of products.
     *
     * @param  int          $limit
     * @param  string|null  $searchTerm
     * @param  mixed        $query
     * @return \Illuminate\Pagination\AbstractPaginator
     */
    public function paginateProducts(
        int $limit,
        string $searchTerm = null,
        $query = null
    ): AbstractPaginator {
        $query = $this->parseQuery($query);

        return $query->search($searchTerm, true)->paginate($limit);
    }

    /**
     * Parse the products query.
     *
     * @param  mixed  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function parseQuery($query = null)
    {
        $baseQuery = $this->getModel()->newQuery();

        if (is_callable($query)) {
            $query = $query($baseQuery);
        }

        return $query ?? $baseQuery;
    }

    /**
     * Creates a product.
     *
     * @param  array  $data
     * @return \App\Domains\Products\Models\Product|null
     */
    public function create(array $data): ?ProductModel
    {
        $product = $this->getModel()->create(
            $this->formatData($data)
        );

        if ($product) {
            resolve(StockMovementContract::class)->update($product, [
                'description' => 'Product creation',
                'qty' => abs($data['qty'] ?? 1),
            ]);
        }

        return $product;
    }

    /**
     * Updates a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  array                                 $data
     * @return bool
     */
    public function update(ProductModel $product, array $data): bool
    {
        return $product->update(
            $this->formatData($data)
        );
    }

    /**
     * Deletes a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @return bool
     */
    public function delete(ProductModel $product): bool
    {
        try {
            return $product->delete();
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Format the product data.
     *
     * @param  array  $data
     * @return array
     */
    public function formatData(array $data): array
    {
        return Arr::only($data, [
            'sku',
            'title',
            'description',
            'price',
            'promotional_price',
        ]);
    }

    /**
     * Get the Product model.
     *
     * @return \App\Domains\Products\Models\Product
     */
    public function getModel(): ProductModel
    {
        return ProductModel::make();
    }
}
