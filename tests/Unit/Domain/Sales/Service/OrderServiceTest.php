<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Sales\Service;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Exception\EmptyOrderException;
use App\Domain\Sales\Exception\InvalidOrderItemQuantityException;
use App\Domain\Sales\Repository\OrderRepository;
use App\Domain\Sales\Service\OrderService;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\ProductId;
use Mockery\MockInterface;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    public function testCreateOrderReturnsCreatedOrder(): void
    {
        $orderForTest = new Order(
            id: null,
            orderItems: new OrderItemsCollection([
                new OrderItem(
                    id: null,
                    orderId: null,
                    productId: new ProductId("1"),
                    price: null,
                    quantity:2,

                )
            ])
        );

        $this->mock(OrderRepository::class, function (MockInterface $mock) use($orderForTest): void {
            $mock
              ->shouldReceive("createOrder")
              ->once()
              ->with($orderForTest)
              ->andReturn(new OrderId("1"));
        });

        $orderId = app(OrderService::class)->createOrder($orderForTest);

        $this->assertInstanceOf(OrderId::class, $orderId);

    }

    public function testCreateOrderThrowExceptionWherOrderEmpty(): void
    {
        $this->expectException(EmptyOrderException::class);
        $this->mock(OrderRepository::class, function (MockInterface $mock) {
            $mock
              ->shouldNotReceive("createOrder")
            ;
        });

        $orderForTest = new Order(
            id: null,
            orderItems: new OrderItemsCollection([])
        );

        app(OrderService::class)->createOrder($orderForTest);
    }

    /**
     * @dataProvider dataProviderForTestOrderItemLessThanZero
     * @param  OrderItem[] $orderItems
     */
    public function testCreateOrderThrowExceptionWherOrderItemQuantityLessThanZero(array $orderItems): void
    {
        $this->expectException(InvalidOrderItemQuantityException::class);
        $this->mock(OrderRepository::class, function (MockInterface $mock) {
            $mock
              ->shouldNotReceive("createOrder")
            ;
        });

        $orderForTest = new Order(
            id: null,
            orderItems: new OrderItemsCollection($orderItems)
        );

        app(OrderService::class)->createOrder($orderForTest);
    }

    public static function dataProviderForTestOrderItemLessThanZero(): array
    {
        return [
            'only a item with quantity zero' => [
                [
                    new OrderItem(
                        id: null,
                        orderId: null,
                        productId: new ProductId("1"),
                        price: null,
                        quantity:2,
    
                    ),

                    new OrderItem(
                        id: null,
                        orderId: null,
                        productId: new ProductId("3"),
                        price: null,
                        quantity:2,
    
                    ),

                    new OrderItem(
                        id: null,
                        orderId: null,
                        productId: new ProductId("3"),
                        price: null,
                        quantity:0,
    
                    ),
                ]
            ],

            'only a item with quantity less that zero' => [
                [
                    new OrderItem(
                        id: null,
                        orderId: null,
                        productId: new ProductId("1"),
                        price: null,
                        quantity:2,
    
                    ),

                    new OrderItem(
                        id: null,
                        orderId: null,
                        productId: new ProductId("3"),
                        price: null,
                        quantity:-1,
    
                    ),

                    new OrderItem(
                        id: null,
                        orderId: null,
                        productId: new ProductId("3"),
                        price: null,
                        quantity:1,
    
                    ),
                ]
            ]

        ];
    }
}
