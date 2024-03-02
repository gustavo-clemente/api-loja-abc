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
     */
    public function testTotalAmountInRealCalculationIsCorrect(OrderItemsCollection $input, float $expectedResult): void
    {
        $order = new Order(
            id: new OrderId('1'),
            orderItems: $input
        );

        $totalAmount = $order->getTotalAmountInReal();

        $this->assertEquals($expectedResult, $totalAmount);
    }

    public static function provideOrderData(): array
    {
        return [
            'test without decimal place values' => [
                new OrderItemsCollection([
                    new OrderItem(
                        id: new OrderItemId('1'),
                        orderId: new OrderId('1'),
                        product: new Product(
                            priceInCents: 10000
                        ),
                        quantity: 1

                    ),
                    new OrderItem(
                        id: new OrderItemId('2'),
                        orderId: new OrderId('2'),
                        product: new Product(
                            priceInCents: 2000
                        ),
                        quantity: 2

                    ),
                    new OrderItem(
                        id: new OrderItemId('3'),
                        orderId: new OrderId('3'),
                        product: new Product(
                            priceInCents: 3000
                        ),
                        quantity: 3

                    ),
                ]),
                230.00
            ],

            'test with decimal place values ' => [
                new OrderItemsCollection([
                    new OrderItem(
                        id: new OrderItemId('1'),
                        orderId: new OrderId('1'),
                        product: new Product(
                            priceInCents: 198654
                        ),
                        quantity: 1

                    ),
                    new OrderItem(
                        id: new OrderItemId('2'),
                        orderId: new OrderId('2'),
                        product: new Product(
                            priceInCents: 198654
                        ),
                        quantity: 1

                    ),
                    new OrderItem(
                        id: new OrderItemId('3'),
                        orderId: new OrderId('3'),
                        product: new Product(
                            priceInCents: 198654
                        ),
                        quantity: 1

                    ),
                ]),
                5959.62
            ],

            'test without any item' => [
                new OrderItemsCollection([]),
                0
            ]
        ];
    }
}
