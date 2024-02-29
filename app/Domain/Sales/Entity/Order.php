<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\OrderId;

class Order
{
    /** @param OrderItem[] $orderItems */
    public function __construct(
        private ?OrderId $id,
        private array $orderItems
    ) {
        
    }

    public function getOrderId(): ?OrderId
    {
        return $this->id;
    }

    /** @return OrderItem[] */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function getTotalAmount(): float
    {
        $totalAmount = 0;

        foreach($this->orderItems as $orderItem){
            $totalAmount += ($orderItem->getQuantity() * $orderItem->getProduct()->getPrice()) ;
        }

        return $totalAmount;
    }
}
