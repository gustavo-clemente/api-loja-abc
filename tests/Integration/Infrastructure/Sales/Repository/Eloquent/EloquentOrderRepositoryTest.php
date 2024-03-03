<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Sales\Repository\Eloquent;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Exception\OrderWithDuplicateProductEntyException;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\ProductId;
use App\Infrastructure\Sales\Model\OrderItemModel;
use App\Infrastructure\Sales\Model\OrderModel;
use App\Infrastructure\Sales\Model\ProductModel;
use App\Infrastructure\Sales\Repository\Eloquent\EloquentOrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentOrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOrderAddOrderWithItems(): void
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

        $orderItemsCollection = new OrderItemsCollection([
            new OrderItem(
                product: new Product(
                    id: new ProductId((string) $productsModels[0]->id)
                ),
                quantity: 2,
            ),
            new OrderItem(
                product: new Product(
                    id: new ProductId((string) $productsModels[1]->id)
                ),
                quantity: 2,
            ),
            new OrderItem(
                product: new Product(
                    id: new ProductId((string) $productsModels[2]->id)
                ),
                quantity: 2,
            )
        ]);

        $order = new Order(
            id: null,
            orderItems: $orderItemsCollection,
            createdAt: null,
            updatedAt: null
        );

        /** @var OrderId */
        $orderId = app(EloquentOrderRepository::class)->createOrder($order);

        $this->assertInstanceOf(OrderId::class, $orderId);

        $this->assertDatabaseCount("orders", 1);
        $this->assertDatabaseCount("order_items", 3);
        $this->assertDatabaseHas("orders", [
            "id" => $orderId->getIdentifier(),
        ]);

        $this->assertDatabaseHas("order_items", [
            "order_id" => $orderId->getIdentifier(),
            "product_id" => $productsModels[0]->id
        ]);

        $this->assertDatabaseHas("order_items", [
            "order_id" => $orderId->getIdentifier(),
            "product_id" => $productsModels[1]->id
        ]);

        $this->assertDatabaseHas("order_items", [
            "order_id" => $orderId->getIdentifier(),
            "product_id" => $productsModels[2]->id
        ]);
        
    }

    public function testFindAllReturnOrderCollection(): void
    {
        $orderModelColection = OrderModel::factory(10)->create([
            "created_at" => now() 
        ]);
        $productModel = ProductModel::factory()->createOne();

        foreach ($orderModelColection as $orderModel) {
            OrderItemModel::factory(2)->create([
                'order_id' => $orderModel->id,
                'product_id' => $productModel->id,
            ]);
        }

        $orderCollection = app(EloquentOrderRepository::class)->findAll();

        $this->assertInstanceOf(OrderCollection::class, $orderCollection);
        $this->assertCount(10, $orderCollection->getItems());

        $order = $orderCollection->getItems()[0];

        $this->assertNotEmpty($order->getOrderId());
        $this->assertNotEmpty($order->getOrderItems());
        $this->assertNotEmpty($order->getCreatedAt());
        $orderItems = $order->getOrderItems()->getItems();

        $orderItem = $orderItems[0];

        $this->assertEquals($order->getOrderId()->getIdentifier(), $orderItem->getOrderId()->getIdentifier());
        $this->assertNotEmpty($orderItem->getProduct()->getId());
        $this->assertNotEmpty($orderItem->getProduct()->getName());
        $this->assertNotEmpty($orderItem->getProduct()->getPriceInCents());
        $this->assertNotEmpty($orderItem->getProduct()->getDescription());
        $this->assertNotEmpty($orderItem->getQuantity());
        $this->assertNotEmpty($orderItem->getCreatedAt());
    }

    public function testFindByIdReturnsOrder(): void
    {
        $orderModel = OrderModel::factory()->createOne();
        $productModel = ProductModel::factory()->createOne();

        OrderItemModel::factory(2)->create([
            'order_id' => $orderModel->id,
            'product_id' => $productModel->id,
        ]);

        $orderId = new OrderId($orderModel->id);

        $order = app(EloquentOrderRepository::class)->findById($orderId);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertSame($orderModel->id, $order->getOrderId()->getIdentifier());


        $this->assertNotEmpty($order->getOrderId());
        $this->assertNotEmpty($order->getOrderItems());
        $this->assertNotEmpty($order->getCreatedAt());
        $orderItems = $order->getOrderItems()->getItems();

        $orderItem = $orderItems[0];

        $this->assertEquals($order->getOrderId()->getIdentifier(), $orderItem->getOrderId()->getIdentifier());
        $this->assertNotEmpty($orderItem->getProduct()->getId());
        $this->assertNotEmpty($orderItem->getProduct()->getName());
        $this->assertNotEmpty($orderItem->getProduct()->getPriceInCents());
        $this->assertNotEmpty($orderItem->getProduct()->getDescription());
        $this->assertNotEmpty($orderItem->getQuantity());
        $this->assertNotEmpty($orderItem->getCreatedAt());

    }

    public function testFindByIdReturnNullWhenNotFound(): void
    {
        $orderId = new OrderId('000');

        $order = app(EloquentOrderRepository::class)->findById($orderId);

        $this->assertNull($order);
    }

    public function testCancelOrderRemoveOrderAndItemsFromDatabase(): void
    {
        $productModel = ProductModel::factory()->createOne();
        $orderModel = OrderModel::factory(10)->create();

        $ordermodelToRemove = $orderModel->first();

        foreach($orderModel as $order) {
            OrderItemModel::factory(2)->create([
                'order_id' => $order,
                'product_id' => $productModel->id,
            ]);
        }


        $orderId = new OrderId($ordermodelToRemove->id);

        $orderId = app(EloquentOrderRepository::class)->cancelOrder($orderId);

        $this->assertInstanceOf(OrderId::class, $orderId);

        $this->assertDatabaseCount('orders', 9);
        $this->assertDatabaseMissing('orders', [
            'id' => $ordermodelToRemove->id
        ]);

        $this->assertDatabaseMissing('order_items',[
            'order_id' => $ordermodelToRemove->id
        ]);

    }

    public function testCancelOrderReturnNullWhenOrderNotFound(): void
    {

        $orderId = new OrderId('000');

        $orderId = app(EloquentOrderRepository::class)->cancelOrder($orderId);

        $this->assertNull($orderId);

    }

    public function testAddOrderItemSaveNewOrderItemsOnDatabase(): void
    {
        $orderModel = OrderModel::factory()->createOne();
        $productModel = ProductModel::factory(4)->create()->toArray();

        OrderItemModel::factory()->createMany([
            [
                'order_id' => $orderModel->id,
                'product_id' => $productModel[0]['id'],
            ],
            [
                'order_id' => $orderModel->id,
                'product_id' => $productModel[1]['id'],
            ]
            
        ]);

        $orderId = new OrderId($orderModel->id);
        $orderItemCollection = new OrderItemsCollection([
            new OrderItem(
                orderId: $orderId,
                product: new Product(
                    id: new ProductId($productModel[2]['id']),
                ),
                quantity: 2
            ),
            new OrderItem(
                orderId: $orderId,
                product: new Product(
                    id: new ProductId($productModel[3]['id']),
                ),
                quantity: 2
            ),
        ]);

        $order = app(EloquentOrderRepository::class)->addOrderItems($orderId, $orderItemCollection);

        $this->assertInstanceOf(Order::class, $order);

        $this->assertCount(4, $order->getOrderItems()->getItems());

        $this->assertDatabaseCount('order_items',4);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId->getIdentifier(),
            'product_id' => $productModel[2]['id']
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId->getIdentifier(),
            'product_id' => $productModel[3]['id']
        ]);
    }

    public function testAddOrderItemReturnNullWhenIdNotFound(): void
    {
        $orderId = new OrderId('0');
        $orderItemCollection = new OrderItemsCollection([]);

        $order = app(EloquentOrderRepository::class)->addOrderItems($orderId, $orderItemCollection);

        $this->assertNull($order);
    }

    public function testAddOrderItemThrowWhenDuplicateProduct(): void
    {
        $this->expectException(OrderWithDuplicateProductEntyException::class);
        $orderModel = OrderModel::factory()->createOne();
        $productModel = ProductModel::factory(3)->create()->toArray();

        OrderItemModel::factory()->createMany([
            [
                'order_id' => $orderModel->id,
                'product_id' => $productModel[0]['id'],
                'quantity' => 10
            ],
            [
                'order_id' => $orderModel->id,
                'product_id' => $productModel[1]['id'],
                'quantity' => 10
            ]
            
        ]);

        $orderId = new OrderId($orderModel->id);
        $orderItemCollection = new OrderItemsCollection([
            new OrderItem(
                orderId: $orderId,
                product: new Product(
                    id: new ProductId($productModel[1]['id']),
                ),
                quantity: 2
            ),
            new OrderItem(
                orderId: $orderId,
                product: new Product(
                    id: new ProductId($productModel[2]['id']),
                ),
                quantity: 2
            ),
        ]);

        app(EloquentOrderRepository::class)->addOrderItems($orderId, $orderItemCollection);


        $this->assertDatabaseCount('order_items',2);
    }
}
