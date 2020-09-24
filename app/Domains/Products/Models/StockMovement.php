<?php

namespace App\Domains\Products\Models;

use App\Support\Domains\Model;

class StockMovement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['qty'];

    /**
     * The product that owns this movement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
