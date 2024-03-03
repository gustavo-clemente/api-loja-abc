<?php

declare(strict_types=1);

namespace App\Domain\Sales\Repository;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\ValueObject\OrderId;

interface OrderRepository
{
    public function createOrder(Order $order): OrderId;

    public function findAll(): OrderCollection;

    public function findById(OrderId $orderId): ?Order;

    public function cancelOrder(OrderId $order): ?OrderId;

    public function addOrderItems(OrderId $orderId, OrderItemsCollection $orderItems): ?Order;
}
