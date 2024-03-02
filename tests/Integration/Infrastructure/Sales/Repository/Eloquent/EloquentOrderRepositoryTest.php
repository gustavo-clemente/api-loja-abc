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
}
