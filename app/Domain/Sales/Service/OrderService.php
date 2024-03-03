<?php

declare(strict_types=1);

namespace App\Domain\Sales\Service;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Exception\OrderNotFoundException;
use App\Domain\Sales\Repository\OrderRepository;
use App\Domain\Sales\ValueObject\OrderId;

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
        $order = $this->orderRepository->findById($orderId);

        if(is_null($order)){
            throw new OrderNotFoundException();
        }

        return $order;
    }

    public function cancelOrder(OrderId $orderId): OrderId
    {
        $orderId = $this->orderRepository->cancelOrder($orderId);

        if(is_null($orderId)){
            throw new OrderNotFoundException();
        }
        
        
        return $orderId;
    }

    public function addOrderItems(OrderId $orderId, OrderItemsCollection $orderItems): Order
    {
        $orderItems->validate();

        $updatedOrder = $this->orderRepository->addOrderItems($orderId, $orderItems);

        if(is_null($updatedOrder)){
            throw new OrderNotFoundException();
        }
        
        return $updatedOrder;
    }
}
