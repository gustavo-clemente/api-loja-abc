<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;

class OrderItem
{
    public function __construct(
        private ?OrderItemId $orderItemId,
        private OrderId $orderId,
        private Product $product,
        private int $quantity
    ) {
    }

    public function getOrderItemId(): ?OrderItemId
    {
        return $this->orderItemId;
    }

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

}
