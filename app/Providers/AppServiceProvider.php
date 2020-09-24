<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\Domains\Search\SimpleSearch;
use App\Support\Domains\Search\AdvancedSearch;
use App\Support\Domains\Search\Contracts\SimpleSearchContract;
use App\Support\Domains\Search\Contracts\AdvancedSearchContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SimpleSearchContract::class,
            SimpleSearch::class
        );

        $this->app->bind(
            AdvancedSearchContract::class,
            AdvancedSearch::class
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
