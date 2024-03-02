<?php

declare(strict_types= 1);

namespace App\Infrastructure\Sales\Repository\Eloquent;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Repository\OrderRepository;
use App\Domain\Sales\ValueObject\OrderId;
use App\Infrastructure\Sales\Model\OrderItemModel;
use App\Infrastructure\Sales\Model\OrderModel;
use App\Infrastructure\Sales\Model\ProductModel;

class EloquentOrderRepository implements OrderRepository
{
    public function createOrder(Order $order): OrderId
    {
        $orderModel = OrderModel::create();

        $orderItemsModels = array_map(function(OrderItem $orderItem){
            $productModel = ProductModel::find($orderItem->getProductId()->getIdentifier());

            return new OrderItemModel([
                "product_id" => $orderItem->getProductId()->getIdentifier(),
                "quantity"=> $orderItem->getQuantity(),
                "price" => $productModel->price
            ]);
        }, $order->getOrderItems()->getItems());

        $orderModel->items()->saveMany($orderItemsModels);

        return new OrderId((string)$orderModel->id);
    }

    public function addOrderItems(OrderItemsCollection $orderItems): Order
    {
        throw new \Exception("Not Implemented");
    }

    public function cancelOrder(OrderId $order): bool
    {
        throw new \Exception("Not Implemented");
    }

    public function findAll(): OrderCollection
    {
        throw new \Exception("Not implemented");
    }

    public function findById(OrderId $orderId): ?Order
    {
        throw new \Exception("Not implemented");
    }
}
