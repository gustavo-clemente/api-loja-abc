<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Domain\Sales\ValueObject\ProductId;
use DateTime;

class OrderItem
{
    public function __construct(
        private ?OrderItemId $id,
        private ?OrderId $orderId,
        private ProductId $productId,
        private ?float $price,
        private int $quantity,
        private ?DateTime $createdAt = null,
        private ?DateTime $updatedAt = null,
    ) {
    }

    public function getOrderItemId(): ?OrderItemId
    {
        return $this->id;
    }

    public function getOrderId(): ?OrderId
    {
        return $this->orderId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getPriceInCents(): int
    {
        return (int) round($this->price * 100);
    }

    public function setPrice(float $price): OrderItem
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

}
