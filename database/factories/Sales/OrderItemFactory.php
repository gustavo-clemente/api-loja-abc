<?php

namespace Database\Factories\Sales;

use App\Infrastructure\Sales\Model\OrderItemModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItemModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "quantity" => $this->faker->numberBetween(1,100),
        ];
    }
}
