<?php

namespace App\Units\Core\Providers;

use App\Domains\Products\Product;
use App\Support\Domains\Search\Search;
use App\Domains\Products\StockMovement;
use Illuminate\Support\ServiceProvider;
use App\Support\Domains\Search\SimpleSearch;
use App\Support\Domains\Search\AdvancedSearch;
use App\Domains\Products\Contacts\ProductContract;
use App\Domains\Products\Contacts\StockMovementContract;
use App\Support\Domains\Search\Contracts\SearchContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SearchContract::class, function ($app) {
            $instance = new Search();

            return $instance->setSimple(new SimpleSearch($instance))
                ->setAdvanced(new AdvancedSearch($instance));
        });

        $this->app->bind(
            ProductContract::class,
            Product::class
        );

        $this->app->bind(
            StockMovementContract::class,
            StockMovement::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
