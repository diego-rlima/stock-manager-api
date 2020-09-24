<?php

namespace App\Domains\Products\Models;

use App\Support\Domains\SearchableModel;

class Product extends SearchableModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'title',
        'description',
        'price',
        'promotional_price',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    protected array $searchable = [
        'sku',
        'title',
        'description',
    ];

    /**
     * The stock movements of this product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'product_id');
    }
}
