<?php

namespace Database\Factories;

use App\Domains\Products\Models\StockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockMovement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'qty' => $this->faker->numberBetween(1, 100),
            'type' => $this->faker->randomElement(['increase', 'decrease']),
            'description' => $this->faker->sentence,
        ];
    }
}
