<?php

declare(strict_types=1);

namespace Tests\Feature\Sales;

use App\Domain\Sales\ValueObject\OrderId;
use App\Infrastructure\Sales\Model\OrderItemModel;
use App\Infrastructure\Sales\Model\OrderModel;
use App\Infrastructure\Sales\Model\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    const BASE_URL = "/api/order";

    use RefreshDatabase;

    public function testPostOrderCreatesOrderWithItems(): void
    {
        $productsModels = [
            ProductModel::factory()->createOne([
                "price_in_cents" => 120000
            ]),
            ProductModel::factory()->createOne([
                "price_in_cents" => 150000
            ]),
            ProductModel::factory()->createOne([
                "price_in_cents" => 180000
            ]),
        ];

        $payload = [
            "items" => [
                [
                    "id" => $productsModels[0]->id,
                    "quantity" => 1
                ],
                [
                    "id" => $productsModels[1]->id,
                    "quantity" => 2,
                ],
                [
                    "id" => $productsModels[2]->id,
                    "quantity" => 3,
                ],
            ]
        ];

        $response = $this->postJson(self::BASE_URL, $payload);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['status', 'id'])
        );
        $responseJson = $response->json();


        $this->assertDatabaseCount("orders", 1);
        $this->assertDatabaseCount("order_items", 3);
        $this->assertDatabaseHas("order_items", [
            "order_id" => $responseJson['id'],
            "product_id" => $productsModels[0]->id
        ]);
        $this->assertDatabaseHas("order_items", [
            "order_id" => $responseJson['id'],
            "product_id" => $productsModels[1]->id
        ]);
        $this->assertDatabaseHas("order_items", [
            "order_id" => $responseJson['id'],
            "product_id" => $productsModels[2]->id
        ]);
    }

    /**
     * @dataProvider providerToReturn422StatusCode
     */
    public function testPostOrderReturns422WhemPayloadIsInvalid(array $payload): void
    {
        $response = $this->postJson(self::BASE_URL, $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(["message", "errors"])
        );
    }

    public static function providerToReturn422StatusCode(): array
    {
        return [
            'without items' => [
                []
            ],

            'with not array items ' => [
                [
                    'items' => 'teste'
                ]
            ],

            'without id in one item' => [
                [
                    'items' => [
                        [
                            'id' => 1,
                            'quantity' => 1,
                        ],
                        [
                            'id' => 2,
                            'quantity' => 1,
                        ],
                        [
                            'quantity' => 1,
                        ]
                    ]
                ]
            ],

            'without quantity in one item' => [
                [
                    'id' => 2,
                    'quantity' => 1,
                ],
                [
                    'id' => 2,
                    'quantity' => 1,
                ],
                [
                    'id' => 2
                ]
            ]

        ];
    }

    public function testGetOrderReturnsAllOrders(): void
    {
        $productModel = ProductModel::factory()->createOne();

        $orderModels = OrderModel::factory(5)->create();

        foreach ($orderModels as $order) {
            OrderItemModel::factory()->create([
                'product_id' => $productModel->id,
                'order_id' => $order->id
            ]);
        }

        $response = $this->getJson(self::BASE_URL);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(
                'items',
                5,
                fn (AssertableJson $json) =>
                $json->hasAll([
                    'id',
                    'amount',
                    'products'
                ])
                    ->has(
                        'products',
                        1,
                        fn (AssertableJson $json) =>
                        $json->hasAll([
                            'id',
                            'orderId',
                            'productId',
                            'name',
                            'price',
                            'quantity'
                        ])->etc()
                    )->etc()
            )
        );
    }

    public function testGetOrderByIdReturnOrder(): void
    {
        $productModel = ProductModel::factory()->createOne();

        $orderModel = OrderModel::factory()->createOne();

        $orderModelId = $orderModel->id;

        OrderItemModel::factory(2)->create([
            'product_id' => $productModel->id,
            'order_id' => $orderModelId
        ]);

        $baseUrl = self::BASE_URL;

        $response = $this->getJson("{$baseUrl}/{$orderModelId}");

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'status',
            'data' => [
                'id',
                'amount',
                'products' => [
                    [
                        'id',
                        'orderId',
                        'productId',
                        'name',
                        'price',
                        'quantity',
                    ],
                    [
                        'id',
                        'orderId',
                        'productId',
                        'name',
                        'price',
                        'quantity',
                    ]
                ]
            ]
        ]);
    }

    public function testGetOrderByIdReturn404WhenNotFound(): void
    {
        $baseUrl = self::BASE_URL;

        $response = $this->getJson("{$baseUrl}/0");

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['status', 'message'])
        );
    }

    public function testDeleteOrderReturnOrderId(): void
    {
        $productModel = ProductModel::factory()->createOne();

        $orderModel = OrderModel::factory()->createOne();

        $orderModelId = $orderModel->id;

        OrderItemModel::factory(2)->create([
            'product_id' => $productModel->id,
            'order_id' => $orderModelId
        ]);

        $baseUrl = self::BASE_URL;

        $response = $this->deleteJson("{$baseUrl}/{$orderModelId}");

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'status',
            'data' => [
                'id'
            ]
        ]);

        $this->assertDatabaseMissing('orders', [
            'id' => $orderModelId
        ]);

        $this->assertDatabaseMissing('order_items', [
            'order_id' => $orderModelId
        ]);
    }

    public function testDeleteOrderReturn404WhenNotFound(): void
    {
        $baseUrl = self::BASE_URL;

        $response = $this->deleteJson("{$baseUrl}/0");

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['status', 'message'])
        );
    }

    public function testUpdateOrderReturnUpdatedOrder(): void
    {
        $orderModel = OrderModel::factory()->createOne();
        $productModel = ProductModel::factory(4)->create()->toArray();
        $orderModelId = $orderModel->id;

        OrderItemModel::factory()->createMany([
            [
                'order_id' => $orderModelId,
                'product_id' => $productModel[0]['id'],
            ],
            [
                'order_id' => $orderModelId,
                'product_id' => $productModel[1]['id'],
            ]

        ]);

        $payload = [
            "items" => [
                [
                    "id" => $productModel[2]['id'],
                    "quantity" => 1
                ],
                [
                    "id" => $productModel[3]['id'],
                    "quantity" => 2,
                ]
            ]
        ];

        $baseUrl = self::BASE_URL;
        $response = $this->patchJson("{$baseUrl}/{$orderModelId}", $payload);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(
                'data',
                fn (AssertableJson $json) =>
                $json->hasAll([
                    'id',
                    'amount',
                    'products'
                ])
                    ->has(
                        'products',
                        4,
                        fn (AssertableJson $json) =>
                        $json->hasAll([
                            'id',
                            'orderId',
                            'productId',
                            'name',
                            'price',
                            'quantity'
                        ])->etc()
                    )->etc()
            )->etc()
        );
    }

    public function testUpdateReturn404WhenNotFound(): void
    {
        $baseUrl = self::BASE_URL;

        $payload = [
            "items" => [
                [
                    "id" => 0,
                    "quantity" => 1
                ],
                [
                    "id" => 1,
                    "quantity" => 2,
                ]
            ]
        ];

        $response = $this->patchJson("{$baseUrl}/0", $payload);

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['status', 'message'])
        );
    }

    /**
     * @dataProvider providerForBusinessRuleViolationsTest
     */
    public function testUpdateReturnErrorWhenBusinessRuleViolation(array $payload, int $expectedErrorCode): void
    {
        $baseUrl = self::BASE_URL;

        $response = $this->patchJson("{$baseUrl}/0", $payload);

        $response->assertStatus($expectedErrorCode);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['status', 'message'])
        );
    }

    public static function providerForBusinessRuleViolationsTest(): array
    {
        return [
            'item with quantity 0' => [
                [
                    "items" => [
                        [
                            "id" => 1,
                            "quantity" => 1
                        ],
                        [
                            "id" => 2,
                            "quantity" => 2,
                        ],
                        [
                            "id" => 2,
                            "quantity" => 0,
                        ]
                    ]
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],

            'item with negative quantity' => [
                [
                    "items" => [
                        [
                            "id" => 1,
                            "quantity" => 1
                        ],
                        [
                            "id" => 2,
                            "quantity" => -6,
                        ],
                        [
                            "id" => 2,
                            "quantity" => 10,
                        ]
                    ]
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'item with duplicate product' => [
                [
                    "items" => [
                        [
                            "id" => 1,
                            "quantity" => 1
                        ],
                        [
                            "id" => 2,
                            "quantity" => 10,
                        ],
                        [
                            "id" => 1,
                            "quantity" => 10,
                        ]
                    ]
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ]
        ];
    }
}
