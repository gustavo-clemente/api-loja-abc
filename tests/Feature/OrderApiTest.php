<?php

declare(strict_types= 1);

namespace Tests\Feature;

use App\Infrastructure\Sales\Model\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
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

        $response = $this->json("POST","/api/order", $payload);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson(
            fn(AssertableJson $json) => 
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
        $response = $this->json("POST","/api/order", $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson(
            fn(AssertableJson $json) =>
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
                    'items'=> [
                        [
                            'id'=> 1,
                            'quantity'=> 1,
                        ],
                        [
                            'id'=> 2,
                            'quantity'=> 1,
                        ],
                        [
                            'quantity'=> 1,
                        ]
                    ]
                ]
            ],

            'without quantity in one item' => [
                [
                    'id'=> 2,
                    'quantity'=> 1,
                ],
                [
                    'id'=> 2,
                    'quantity'=> 1,
                ],
                [
                    'id'=> 2
                ]
            ]

        ];
    }

}
