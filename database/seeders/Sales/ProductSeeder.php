<?php

namespace Database\Seeders\Sales;

use App\Infrastructure\Sales\Model\ProductModel;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductModel::factory()->count(10)->create();
    }
}
