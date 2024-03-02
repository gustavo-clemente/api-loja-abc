<?php

declare(strict_types= 1);

namespace Tests\Integration\Infrastructure\Sales\Repository\Eloquent;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\ProductId;
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
                "price" => 1200
            ]),
            ProductModel::factory()->createOne([
                "price" => 1500
            ]),
            ProductModel::factory()->createOne([
                "price" => 1800
            ]),
        ];
        
        $orderItemsCollection = new OrderItemsCollection([
            new OrderItem(
                id: null,
                orderId: null,
                productId: new ProductId((string) $productsModels[0]->id),
                price: null,
                quantity: 2,
                createdAt: null,
                updatedAt: null
            ),
            new OrderItem(
                id: null,
                orderId: null,
                productId: new ProductId((string) $productsModels[1]->id),
                price: null,
                quantity: 2,
                createdAt: null,
                updatedAt: null
            ),
            new OrderItem(
                id: null,
                orderId: null,
                productId: new ProductId((string) $productsModels[2]->id),
                price: null,
                quantity: 2,
                createdAt: null,
                updatedAt: null
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
            "product_id" => $productsModels[0]->id,
            "price" => $productsModels[0]->price
        ]);

        $this->assertDatabaseHas("order_items", [
            "order_id" => $orderId->getIdentifier(),
            "product_id" => $productsModels[1]->id,
            "price" => $productsModels[1]->price
        ]);

        $this->assertDatabaseHas("order_items", [
            "order_id" => $orderId->getIdentifier(),
            "product_id" => $productsModels[2]->id,
            "price" => $productsModels[2]->price
        ]);
    }
}
