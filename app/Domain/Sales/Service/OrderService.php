<?php

declare(strict_types=1);

namespace App\Domain\Sales\Service;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Repository\OrderRepository;
use App\Domain\Sales\ValueObject\OrderId;
use Exception;

class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {
        
    }

    public function createOrder(Order $order): OrderId
    {
        $order->validate();
        return $this->orderRepository->createOrder($order);
    }

    public function getAllOrders(): OrderCollection
    {
        return $this->orderRepository->findAll();
    }

    public function getOrderById(OrderId $orderId): Order
    {
        throw new Exception('Not Implemented');
    }

    public function cancelOrder(OrderId $orderId): void
    {
        throw new Exception('Not Implemented');
    }

    /** @param OrderItem[] $orderItems*/
    public function addOrderItems(array $orderItems): Order
    {
        throw new Exception('Not Implemented');
    }
}
