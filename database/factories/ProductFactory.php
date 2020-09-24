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
            'sku' => $this->faker->unique()->optional()->randomNumber(),
            'description' => $this->faker->optional()->paragraphs(3, true),
            'price' => $this->faker->randomFloat(),
            'promotional_price' => $this->faker->optional()->randomFloat(),
            'in_stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
