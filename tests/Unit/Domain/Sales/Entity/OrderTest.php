<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Sales\Entity;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Domain\Sales\ValueObject\ProductId;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /**
     * @dataProvider provideOrderData
     * @param OrderItem[] $input
     */
    public function testTotalAmountCalculationIsCorrect(array $input, float $expectedResult): void
    {
        $order = new Order(
            id: new OrderId('1'),
            orderItems: $input
        );

        $totalAmount = $order->getTotalAmount();

        $this->assertEquals($expectedResult, $totalAmount);
    }

    public function provideOrderData(): array
    {
        return [
            'test with integer values' => [
                [
                    new OrderItem(
                        orderItemId: new OrderItemId('1'),
                        orderId: new OrderId('1'),
                        product: new Product(
                            id: new ProductId('1'),
                            name: 'Product 1',
                            price: 500,
                            description: 'Product 1 Description'
                        ),
                        quantity: 1

                    ),
                    new OrderItem(
                        orderItemId: new OrderItemId('2'),
                        orderId: new OrderId('2'),
                        product: new Product(
                            id: new ProductId('2'),
                            name: 'Product 2',
                            price: 500,
                            description: 'Product 2 Description'
                        ),
                        quantity: 2

                    ),
                    new OrderItem(
                        orderItemId: new OrderItemId('3'),
                        orderId: new OrderId('3'),
                        product: new Product(
                            id: new ProductId('3'),
                            name: 'Product 3',
                            price: 100,
                            description: 'Product 3 Description'
                        ),
                        quantity: 3

                    ),
                ],
                1800.00
            ],
            'test with float values' => [
                [
                    new OrderItem(
                        orderItemId: new OrderItemId('1'),
                        orderId: new OrderId('1'),
                        product: new Product(
                            id: new ProductId('1'),
                            name: 'Product 4',
                            price: 250.50,
                            description: 'Product 4 Description'
                        ),
                        quantity: 1

                    ),
                    new OrderItem(
                        orderItemId: new OrderItemId('2'),
                        orderId: new OrderId('2'),
                        product: new Product(
                            id: new ProductId('2'),
                            name: 'Product 5',
                            price: 274.35,
                            description: 'Product 5 Description'
                        ),
                        quantity: 1

                    ),
                    new OrderItem(
                        orderItemId: new OrderItemId('3'),
                        orderId: new OrderId('3'),
                        product: new Product(
                            id: new ProductId('3'),
                            name: 'Product 6',
                            price: 339.98,
                            description: 'Product 6 Description'
                        ),
                        quantity: 1

                    ),
                ],
                864.83
            ],
            'test with float values with more precision' => [
                [
                    new OrderItem(
                        orderItemId: new OrderItemId('1'),
                        orderId: new OrderId('1'),
                        product: new Product(
                            id: new ProductId('1'),
                            name: 'Product 7',
                            price: 5.99,
                            description: 'Product 7 Description'
                        ),
                        quantity: 1

                    ),
                    new OrderItem(
                        orderItemId: new OrderItemId('2'),
                        orderId: new OrderId('2'),
                        product: new Product(
                            id: new ProductId('2'),
                            name: 'Product 8',
                            price: 15.49,
                            description: 'Product 8 Description'
                        ),
                        quantity: 1

                    ),
                    new OrderItem(
                        orderItemId: new OrderItemId('3'),
                        orderId: new OrderId('3'),
                        product: new Product(
                            id: new ProductId('3'),
                            name: 'Product 9',
                            price: 42.75,
                            description: 'Product 9 Description'
                        ),
                        quantity: 1

                    ),
                ],
                64.23
            ],

            'test without any item' => [
                [],
                0
            ]
        ];
    }
}
