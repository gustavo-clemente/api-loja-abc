<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\Exception\EmptyOrderException;
use App\Domain\Sales\Exception\InvalidOrderItemQuantityException;
use App\Domain\Sales\Exception\OrderWithDuplicateProductEntyException;
use App\Domain\Sales\ValueObject\OrderId;
use DateTime;

class Order implements \JsonSerializable
{
    public function __construct(
        private ?OrderId $id = null,
        private ?OrderItemsCollection $orderItems = null,
        private ?DateTime $createdAt = null,
        private ?DateTime $updatedAt = null,
    ) {
        
    }

    public function getOrderId(): ?OrderId
    {
        return $this->id;
    }

    public function getOrderItems(): ?OrderItemsCollection
    {
        return $this->orderItems;
    }

    public function getTotalAmountInReal(): float
    {
        $totalAmountInCents = 0;

        if(is_null($this->orderItems)){
            $totalAmountInCents = 0;
        }

        foreach($this->orderItems->getItems() as $orderItem){
            $totalAmountInCents += ($orderItem->getQuantity() * $orderItem->getProduct()->getPriceInCents()) ;
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
        $this->orderItems->validate();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getOrderId()->getIdentifier(),
            'amount' => $this->getTotalAmountInReal(),
            'products' => $this->orderItems->jsonSerialize()
        ];
    }
}
