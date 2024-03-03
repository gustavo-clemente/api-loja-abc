<?php

declare(strict_types= 1);

namespace App\Application\Sales;

use App\Application\Sales\Input\CreateOrderInput;
use App\Application\Sales\Input\GetOrderByIdInput;
use App\Application\Sales\Output\CreateOrderOutput;
use App\Application\Sales\Output\FindAllOrdersOutput;
use App\Application\Sales\Output\GetOrderByIdOutput;
use App\Domain\Sales\Service\OrderService;

class OrderApplication
{
    public function __construct(
        private OrderService $orderService
    ) {
    }

    public function createOrder(CreateOrderInput $createOrderInput): CreateOrderOutput
    {
        $orderId = $this->orderService->createOrder($createOrderInput->getOrder());
        return new CreateOrderOutput($orderId);
    }

    public function getAll(): FindAllOrdersOutput
    {
        $ordersCollection = $this->orderService->getAllOrders();

        return new FindAllOrdersOutput($ordersCollection);
    }

    public function getById(GetOrderByIdInput $getOrderByIdInput): GetOrderByIdOutput
    {
        $order = $this->orderService->getOrderById($getOrderByIdInput->getOrderId());

        return new GetOrderByIdOutput($order);
    }
}
