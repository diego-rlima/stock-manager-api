<?php

namespace App\Domains\Products\Models;

use Exception;
use Carbon\Carbon;
use App\Support\Domains\SearchableModel;
use Database\Factories\StockMovementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Domains\Search\Contracts\SearchContract;
use App\Support\Domains\Search\Contracts\AdvancedSearchContract;

class StockMovement extends SearchableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['qty'];

    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    protected array $searchable = [];

    /**
     * The product that owns this movement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return StockMovementFactory::new();
    }

    /**
     * Add the search mutators.
     *
     * @param  \App\Support\Domains\Search\Contracts\SearchContract  $search
     * @return void
     */
    protected function searchMutators(SearchContract $search): void
    {
        $search->addMutator('created_at', function ($value) {
            try {
                return !empty($value)
                    ? Carbon::parse($value)->toDateString()
                    : null;
            } catch (Exception $exception) {
                return null;
            }
        });
    }

    /**
     * Configure the advanced search columns.
     *
     * @param  \App\Support\Domains\Search\Contracts\AdvancedSearchContract  $search
     * @return void
     */
    protected function searchAdvancedSettings(AdvancedSearchContract $search): void
    {
        $search->addColumn('start', 'created_at', '>=')
            ->addColumn('end', 'created_at', '<=');
    }
}
