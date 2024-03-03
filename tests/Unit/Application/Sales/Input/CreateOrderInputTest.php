<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Sales\Input;

use App\Application\Sales\Input\CreateOrderInput;
use App\Domain\Sales\Entity\Order;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateOrderInputTest extends TestCase
{
    public function testGetOrderReturnsCorrectOrder(): void
    {   
        $data = [
            "items" => [
                [
                    "id" => 1,
                    "quantity" => 1
                ],
                [
                    "id" => 2,
                    "quantity" => 2
                ],
                [
                    "id" => 3,
                    "quantity" => 3
                ]
            ]
        ];
        
        $createOrderInput = new CreateOrderInput($data);

        $order = $createOrderInput->getOrder();

        $this->assertInstanceOf(Order::class, $order);
        $orderItems = $order->getOrderItems()->getItems();

        $totalDataItems = count($data['items']);

        $this->assertCount($totalDataItems, $orderItems);
        $this->assertEquals($data['items'][0]['id'], $orderItems[0]->getProduct()->getId()->getIdentifier());
        $this->assertEquals($data['items'][0]['quantity'], $orderItems[0]->getQuantity());
    }

    

    /**
     * @dataProvider providerForInvalidArrayItems
     */
    public function testInputThrowErrorWithInvalidArrayItems(array $data): void
    {
        $this->expectException(ValidationException::class);

        $orderinput = new CreateOrderInput($data);


    }

    public static function providerForInvalidArrayItems(): array
    {
        return [
            "test without quantity on one item" => [
                [
                    "items" => [
                        [
                            "id" => 1,
                            "quantity" => 1
                        ],
                        [
                            "id" => 2
                        ],
                        [
                            "id" => 3,
                            "quantity" => 3
                        ]
                    ]
                ]
            ],

            "test without id on one item" => [
                [
                    "items" => [
                        [
                            "id" => "1",
                            "quantity" => "1"
                        ],
                        [
                            "id" => "2",
                            "quantity" => "2"
                        ],
                        [
                            "quantity" => "3"
                        ]
                    ]
                ]
            ],

            "test without items" => [
                [
                ]
            ]
        ];
    }

}
