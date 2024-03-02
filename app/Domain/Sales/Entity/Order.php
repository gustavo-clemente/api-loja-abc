<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\Exception\EmptyOrderException;
use App\Domain\Sales\Exception\InvalidOrderItemQuantityException;
use App\Domain\Sales\Exception\OrderWithDuplicateProductEntyException;
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

    public function getTotalAmountInReal(): float
    {
        $totalAmountInCents = 0;

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
        $this->validateOrderNotEmpty();
        $this->validateOrderItemsQuantity();
        $this->validateUniqueProductEntries();
    }

    private function validateOrderNotEmpty(): void
    {
        if($this->orderItems->isEmpty())
        {
            throw new EmptyOrderException("Order must contain at least one item");
        }

    }

    private function validateOrderItemsQuantity(): void
    {
        foreach($this->orderItems->getItems() as $orderItem){
            if($orderItem->getQuantity() <= 0){
                throw new InvalidOrderItemQuantityException("Item quantity cannot be less or equal zero");
            }
        }
    }

    private function validateUniqueProductEntries(): void
    {
        $productItemsIds = [];
        
        foreach($this->orderItems->getItems() as $orderItem){

            $productIdentifier = $orderItem->getProduct()->getId()->getIdentifier();

            if(in_array($productIdentifier, $productItemsIds)){
                throw new OrderWithDuplicateProductEntyException(
                    "A order should not have two order Items with same product id. Specify quantity"
                );
            }

            $productItemsIds[] = $productIdentifier;
        }
    }
}
