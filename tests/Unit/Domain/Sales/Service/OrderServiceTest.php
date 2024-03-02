<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Sales\Service;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Exception\EmptyOrderException;
use App\Domain\Sales\Exception\InvalidOrderItemQuantityException;
use App\Domain\Sales\Exception\OrderWithDuplicateProductEntyException;
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
                    product: new Product(
                        id: new ProductId("1"),
                    ),
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
    public function testCreateOrderThrowExceptionWhenOrderItemQuantityLessThanZero(array $orderItems): void
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

    public function testCreateOrderThrowExceptionWhenHasDuplicateProductId(): void
    {
        $this->expectException(OrderWithDuplicateProductEntyException::class);
        $this->mock(OrderRepository::class, function (MockInterface $mock) {
            $mock
              ->shouldNotReceive("createOrder")
            ;
        });

        $orderForTest = new Order(
            id: null,
            orderItems: new OrderItemsCollection([
                new OrderItem(
                    id: null,
                    orderId: null,
                    product: new Product(
                        id: new ProductId('1'),
                    ),
                    quantity:2,

                ),
                new OrderItem(
                    id: null,
                    orderId: null,
                    product: new Product(
                        id: new ProductId('13'),
                    ),
                    quantity:3,

                ),
                new OrderItem(
                    id: null,
                    orderId: null,
                    product: new Product(
                        id: new ProductId('1'),
                    ),
                    quantity:3,

                )
            ])
        );

        app(OrderService::class)->createOrder($orderForTest);
    }

    public function testGetAllOrdersReturnsOrderCollection(): void
    {
        $this->mock(OrderRepository::class, function (MockInterface $mock): void {
            $mock
            ->shouldReceive("findAll")
            ->once()
            ->andReturn(new OrderCollection([]));
        });

        $orders = app(OrderService::class)->getAllOrders();

        $this->assertInstanceOf(OrderCollection::class, $orders);
    }

    public static function dataProviderForTestOrderItemLessThanZero(): array
    {
        return [
            'only a item with quantity zero' => [
                [
                    new OrderItem(
                        id: null,
                        orderId: null,
                        product: new Product(
                            id: new ProductId('1'),
                        ),
                        quantity:2,
    
                    ),

                    new OrderItem(
                        id: null,
                        orderId: null,
                        product: new Product(
                            id: new ProductId('2'),
                        ),
                        quantity:2,
    
                    ),

                    new OrderItem(
                        id: null,
                        orderId: null,
                        product: new Product(
                            id: new ProductId('3'),
                        ),
                        quantity:0,
    
                    ),
                ]
            ],

            'only a item with quantity less that zero' => [
                [
                    new OrderItem(
                        id: null,
                        orderId: null,
                        product: new Product(
                            id: new ProductId('1'),
                        ),
                        quantity:2,
    
                    ),

                    new OrderItem(
                        id: null,
                        orderId: null,
                        product: new Product(
                            id: new ProductId('2'),
                        ),
                        quantity:-1,
    
                    ),

                    new OrderItem(
                        id: null,
                        orderId: null,
                        product: new Product(
                            id: new ProductId('3'),
                        ),
                        quantity:1,
    
                    ),
                ]
            ]

        ];
    }
}
