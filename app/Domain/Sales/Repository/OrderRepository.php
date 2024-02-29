<?php

declare(strict_types=1);

namespace App\Domain\Sales\Repository;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\ValueObject\OrderId;

interface OrderRepository
{
    public function createOrder(Order $order): Order;

    /** @return Order[] */
    public function findAll(): array;

    public function findById(OrderId $orderId): ?Order;

    public function cancelOrder(OrderId $order): bool;

    /** @param OrderItem[] $orderItems*/
    public function addOrderItems(array $orderItems): ?Order;
}
