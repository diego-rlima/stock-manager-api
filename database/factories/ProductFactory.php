<?php

namespace Database\Factories;

use App\Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'sku' => $this->faker->unique()->optional()->randomNumber(3),
            'description' => $this->faker->optional()->paragraphs(3, true),
            'price' => $this->faker->randomFloat(2, 500, 800),
            'promotional_price' => $this->faker->optional()->randomFloat(2, 400, 500),
            'in_stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
