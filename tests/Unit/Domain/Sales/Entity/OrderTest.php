<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Sales\Entity;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
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
    public function testTotalAmountCalculationIsCorrect(OrderItemsCollection $input, float $expectedResult): void
    {
        $order = new Order(
            id: new OrderId('1'),
            orderItems: $input
        );

        $totalAmount = $order->getTotalAmount();

        $this->assertEquals($expectedResult, $totalAmount);
    }

    public static function provideOrderData(): array
    {
        return [
            'test with integer values' => [
                new OrderItemsCollection([
                    new OrderItem(
                        id: new OrderItemId('1'),
                        orderId: new OrderId('1'),
                        productId: new ProductId('1'),
                        price: 500,
                        quantity: 1

                    ),
                    new OrderItem(
                        id: new OrderItemId('2'),
                        orderId: new OrderId('2'),
                        productId: new ProductId('2'),
                        price: 500,
                        quantity: 2

                    ),
                    new OrderItem(
                        id: new OrderItemId('3'),
                        orderId: new OrderId('3'),
                        productId: new ProductId('3'),
                        price: 100,
                        quantity: 3

                    ),
                ]),
                1800.00
            ],
            'test with float values' => [
                new OrderItemsCollection([
                    new OrderItem(
                        id: new OrderItemId('1'),
                        orderId: new OrderId('1'),
                        productId: new ProductId('1'),
                        price: 250.50,
                        quantity: 1

                    ),
                    new OrderItem(
                        id: new OrderItemId('2'),
                        orderId: new OrderId('2'),
                        productId: new ProductId('2'),
                        price: 274.35,
                        quantity: 1

                    ),
                    new OrderItem(
                        id: new OrderItemId('3'),
                        orderId: new OrderId('3'),
                        productId: new ProductId('3'),
                        price: 339.98,
                        quantity: 1

                    ),
                ]),
                864.83
            ],
            'test with float values with more precision' => [
                new OrderItemsCollection([
                    new OrderItem(
                        id: new OrderItemId('1'),
                        orderId: new OrderId('1'),
                        productId: new ProductId('1'),
                        price: 5.99,
                        quantity: 1

                    ),
                    new OrderItem(
                        id: new OrderItemId('2'),
                        orderId: new OrderId('2'),
                        productId: new ProductId('2'),
                        price: 5.99,
                        quantity: 1

                    ),
                    new OrderItem(
                        id: new OrderItemId('3'),
                        orderId: new OrderId('3'),
                        productId: new ProductId('3'),
                        price: 42.75,
                        quantity: 1

                    ),
                ]),
                54.73
            ],

            'test without any item' => [
                new OrderItemsCollection([]),
                0
            ]
        ];
    }
}
