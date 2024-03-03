<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Sales;

use App\Application\Sales\Input\CreateOrderInput;
use App\Application\Sales\Input\GetOrderByIdInput;
use App\Application\Sales\OrderApplication;
use App\Application\Sales\Output\CreateOrderOutput;
use App\Application\Sales\Output\FindAllOrdersOutput;
use App\Application\Sales\Output\GetOrderByIdOutput;
use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Service\OrderService;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Domain\Sales\ValueObject\ProductId;
use DateTime;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderApplicationTest extends TestCase
{
    public function testCreateOrderReturnsCorrectOutput(): void
    {
        $inputCreateOrder = new CreateOrderInput([
            "items" => [
                [
                    "id" => 1,
                    "quantity" => 1
                ]
            ]
        ]);

        $orderId = new OrderId('1');

        $this->mock(OrderService::class, function (MockInterface $mock) use ($orderId) {
            $mock
                ->shouldReceive("createOrder")
                ->once()
                ->andReturn($orderId);
        });

        $output = app(OrderApplication::class)->createOrder($inputCreateOrder);

        $this->assertInstanceOf(CreateOrderOutput::class, $output);
        $this->assertIsArray($output->jsonSerialize());

        $outputSerialized = $output->jsonSerialize();

        $this->assertArrayHasKey('status', $outputSerialized);
        $this->assertArrayHasKey('id', $outputSerialized);
        $this->assertEquals(Response::HTTP_CREATED, $outputSerialized['status']);
        $this->assertEquals($orderId->getIdentifier(), $outputSerialized['id']);
    }

    public function testGetAllReturnsCorrectOutput(): void
    {
        $ordersToBeGenerate = 2;
        $orderCollection = $this->generateOrderCollectionForTest(ordersToBeGenerate: $ordersToBeGenerate);

        $this->mock(OrderService::class, function (MockInterface $mock) use ($orderCollection) {
            $mock
                ->shouldReceive('getAllOrders')
                ->once()
                ->andReturn($orderCollection);
        });

        $output = app(OrderApplication::class)->getAll();

        $orderForAsserts = $orderCollection->getItems()[0];

        $this->assertInstanceOf(FindAllOrdersOutput::class, $output);
        $outputSerialized = $output->jsonSerialize();

        $this->assertArrayHasKey('items', $outputSerialized);
        $this->assertCount($ordersToBeGenerate, $outputSerialized['items']);

        $outputItemForAsserts = $outputSerialized['items'][0];

        $this->assertEquals($orderForAsserts->getOrderId()->getIdentifier(), $outputItemForAsserts['id']);
        $this->assertEquals($orderForAsserts->getTotalAmountInReal(), $outputItemForAsserts['amount']);

        $this->assertCount(count($orderForAsserts->getOrderItems()->getItems()), $outputItemForAsserts['products']);
    }

    public function testGetByIdReturnsCorrectOutput(): void
    {
        $order = new Order(
            id: new OrderId('1'),
            orderItems: new OrderItemsCollection([
                new OrderItem(
                    id: new OrderItemId('1'),
                    orderId: new OrderId('1'),
                    product: new Product(
                        id: new ProductId('1'),
                        name: 'p1',
                        priceInCents: 500000,
                        description: '',
                        createdAt: new DateTime(),
                        updatedAt: new DateTime(),

                    ),
                    quantity: 2,
                    createdAt: new DateTime(),
                    updatedAt: new DateTime(),
                )
            ])
        );

        $this->mock(OrderService::class, function (MockInterface $mock) use ($order) {
            $mock
                ->shouldReceive('getOrderById')
                ->once()
                ->andReturn($order);
        });

        $input = new GetOrderByIdInput('1');
        $output = app(OrderApplication::class)->getById($input);

        $this->assertInstanceOf(GetOrderByIdOutput::class, $output);
    }

    private function generateOrderCollectionForTest(int $ordersToBeGenerate = 1): OrderCollection
    {
        $orders = [];

        for ($i = 0; $i < $ordersToBeGenerate; $i++) {
            $orders[] = new Order(
                id: new OrderId((string) $i),
                orderItems: new OrderItemsCollection([
                    new OrderItem(
                        id: new OrderItemId((string) $i),
                        orderId: new OrderId((string) $i),
                        product: new Product(
                            id: new ProductId((string) $i),
                            name: (string) $i,
                            priceInCents: 500000,
                            description: '',
                            createdAt: new DateTime(),
                            updatedAt: new DateTime(),

                        ),
                        quantity: 2,
                        createdAt: new DateTime(),
                        updatedAt: new DateTime(),
                    )
                ])
            );
        }

        return new OrderCollection($orders);
    }
}
