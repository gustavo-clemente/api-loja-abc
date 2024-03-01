<?php

declare(strict_types=1);

namespace App;

use App\Infrastructure\Sales\Model\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function testGetProductReturnsAllProducts(): void
    {
        ProductModel::factory(10)->create();

        $response = $this->getJson('/api/product');

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(
                'items',
                10,
                fn (AssertableJson $json) =>
                $json->hasAll([
                    'id',
                    'name',
                    'price',
                    'description',
                    'createdAt',
                    'updatedAt'
                ])->etc()
            )
        );
    }
}
