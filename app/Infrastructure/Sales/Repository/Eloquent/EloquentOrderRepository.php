<?php

declare(strict_types=1);

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
use Illuminate\Support\Facades\DB;

class EloquentOrderRepository implements OrderRepository
{
    public function createOrder(Order $order): OrderId
    {
        DB::beginTransaction();

        $orderModel = OrderModel::create();

        $orderItemsModels = [];

        foreach($order->getOrderItems()->getItems() as $orderItem){
            $orderItemsModels[] = [
                "order_id" => $orderModel->id,
                "product_id" => $orderItem->getProduct()->getId()->getIdentifier(),
                "quantity" => $orderItem->getQuantity()
            ];
        }

        OrderItemModel::insert($orderItemsModels);

        Db::commit();

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
