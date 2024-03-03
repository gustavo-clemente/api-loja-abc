<?php

namespace Database\Factories\Sales;

use App\Infrastructure\Sales\Model\ProductModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class ProductFactory extends Factory
{
    protected $model = ProductModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'price_in_cents' => $this->faker->numberBetween(100, 9999900),
            'description' => $this->faker->text,
        ];
    }
}
