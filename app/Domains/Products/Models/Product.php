<?php

namespace App\Domains\Products\Models;

use Database\Factories\ProductFactory;
use App\Support\Domains\SearchableModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends SearchableModel
{
    use HasFactory, SoftDeletes;

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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}
