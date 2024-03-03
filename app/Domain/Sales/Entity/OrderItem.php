<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Domain\Sales\ValueObject\ProductId;
use DateTime;

class OrderItem implements \JsonSerializable
{
    public function __construct(
        private ?OrderItemId $id = null,
        private ?OrderId $orderId = null,
        private ?Product $product = null,
        private ?int $quantity = null,
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getQuantity(): ?int
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

    public function jsonSerialize(): array
    {
        return [
            'orderId' => $this->getOrderId()->getIdentifier(),
            'productId' => $this->getProduct()->getId()->getIdentifier(),
            'name' => $this->getProduct()->getName(),
            'price' => $this->getProduct()->getPriceInReal(),
            'quantity' => $this->getQuantity(),
        ];
    }

}
