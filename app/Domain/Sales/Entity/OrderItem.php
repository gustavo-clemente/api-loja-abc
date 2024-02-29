<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Domain\Sales\ValueObject\ProductId;

class OrderItem
{
    public function __construct(
        private ?OrderItemId $orderItemId,
        private OrderId $orderId,
        private ProductId $productId,
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

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

}
