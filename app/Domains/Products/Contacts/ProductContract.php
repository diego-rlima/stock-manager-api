<?php

namespace App\Domains\Products\Contacts;

use App\Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use App\Support\Domains\Contracts\ServiceContract;

interface ProductContract extends ServiceContract
{
    /**
     * Find a product by its ID.
     *
     * @param  int   $id
     * @param  bool  $throwable
     * @return \App\Domains\Products\Models\Product|null
     */
    public function findById(int $id, bool $throwable = true): ?Product;

    /**
     * Find a product by its SKU.
     *
     * @param  string  $sku
     * @param  bool    $throwable
     * @return \App\Domains\Products\Models\Product|null
     */
    public function findBySku(string $sku, bool $throwable = true): ?Product;

    /**
     * Get a list of products.
     *
     * @param  int          $limit
     * @param  string|null  $searchTerm
     * @param  mixed        $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listProducts(int $limit, string $searchTerm = null, $query = null): Collection;

    /**
     * Get a paginated list of products.
     *
     * @param  int          $limit
     * @param  string|null  $searchTerm
     * @param  mixed        $query
     * @return \Illuminate\Pagination\AbstractPaginator
     */
    public function paginateProducts(int $limit, string $searchTerm = null, $query = null): AbstractPaginator;

    /**
     * Creates a product.
     *
     * @param  array  $data
     * @return \App\Domains\Products\Models\Product|null
     */
    public function create(array $data): ?Product;

    /**
     * Updates a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @param  array                                 $data
     * @return bool
     */
    public function update(Product $product, array $data): bool;

    /**
     * Deletes a product.
     *
     * @param  \App\Domains\Products\Models\Product  $product
     * @return bool
     */
    public function delete(Product $product): bool;

    /**
     * Format the product data.
     *
     * @param  array  $data
     * @return array
     */
    public function formatData(array $data): array;

    /**
     * Get the Product model.
     *
     * @return \App\Domains\Products\Models\Product
     */
    public function getModel(): Product;

    /**
     * Parse the products query.
     *
     * @param  mixed  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function parseQuery($query = null);
}
