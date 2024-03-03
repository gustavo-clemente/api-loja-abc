<?php

declare(strict_types=1);

namespace App\Domain\Sales\Service;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Exception\OrderNotFound;
use App\Domain\Sales\Exception\OrderNotFoundException;
use App\Domain\Sales\Repository\OrderRepository;
use App\Domain\Sales\ValueObject\OrderId;
use Exception;
use Symfony\Component\HttpFoundation\Response;

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
            throw new OrderNotFoundException("Order id not found", Response::HTTP_NOT_FOUND);
        }

        return $order;
    }

    public function cancelOrder(OrderId $orderId): OrderId
    {
        $orderId = $this->orderRepository->cancelOrder($orderId);

        if(is_null($orderId)){
            throw new OrderNotFoundException("Order id not found", Response::HTTP_NOT_FOUND);
        }
        
        
        return $orderId;
    }

    /** @param OrderItem[] $orderItems*/
    public function addOrderItems(array $orderItems): Order
    {
        throw new Exception('Not Implemented');
    }
}
