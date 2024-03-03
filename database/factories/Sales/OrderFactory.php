<?php

namespace Database\Factories\Sales;

use App\Infrastructure\Sales\Model\OrderModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class OrderFactory extends Factory
{
    protected $model = OrderModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
        ];
    }
}
