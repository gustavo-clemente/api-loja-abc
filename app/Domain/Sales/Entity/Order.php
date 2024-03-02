<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\Exception\EmptyOrderException;
use App\Domain\Sales\Exception\InvalidOrderItemQuantityException;
use App\Domain\Sales\ValueObject\OrderId;
use DateTime;

class Order
{
    public function __construct(
        private ?OrderId $id,
        private OrderItemsCollection $orderItems,
        private ?DateTime $createdAt = null,
        private ?DateTime $updatedAt = null,
    ) {
        
    }

    public function getOrderId(): ?OrderId
    {
        return $this->id;
    }

    public function getOrderItems(): OrderItemsCollection
    {
        return $this->orderItems;
    }

    public function getTotalAmount(): float
    {
        $totalAmountInCents = 0;

        foreach($this->orderItems->getItems() as $orderItem){
            $totalAmountInCents += ($orderItem->getQuantity() * $orderItem->getPriceInCents()) ;
        }

        return $totalAmountInCents / 100;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function validate(): void
    {
        if($this->orderItems->isEmpty())
        {
            throw new EmptyOrderException("Order must contain at least one item");
        }

        foreach($this->orderItems->getItems() as $orderItem){
            if($orderItem->getQuantity() <= 0){
                throw new InvalidOrderItemQuantityException("Item quantity cannot be less or equal zero");
            }
        }
    }
}
