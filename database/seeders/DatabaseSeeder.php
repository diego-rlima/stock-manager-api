<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Products\Models\Product;
use App\Domains\Products\Models\StockMovement;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        Product::factory()
            ->times(20)
            ->has(
                StockMovement::factory()
                    ->count(3)
                    ->state(function (array $attributes, Product $product) {
                        return $attributes + ['product_id' => $product->id];
                    })
            )
            ->create();
    }
}
