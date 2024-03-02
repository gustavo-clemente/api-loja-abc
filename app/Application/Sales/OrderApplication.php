<?php

declare(strict_types= 1);

namespace App\Application\Sales;

use App\Application\Sales\Input\CreateOrderInput;
use App\Application\Sales\Output\CreateOrderOutput;
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
}
