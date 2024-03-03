<?php

declare(strict_types= 1);

namespace App\Domain\Sales\Entity;
use App\Domain\Shared\Entity\Collection;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Exception\EmptyOrderException;
use App\Domain\Sales\Exception\InvalidOrderItemQuantityException;
use App\Domain\Sales\Exception\OrderWithDuplicateProductEntyException;

/** @method OrderItem[] getItems() */
class OrderItemsCollection extends Collection implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return array_map(function(OrderItem $orderItem){
            return $orderItem->jsonSerialize();
        }, $this->getItems() ?? []);
    }

    public function validate(): void
    {
        $this->validateOrderItemsNotEmpty();
        $this->validateOrderItemsQuantity();
        $this->validateUniqueProductEntries();
    }

    private function validateOrderItemsNotEmpty(): void
    {
        if($this->isEmpty())
        {
            throw new EmptyOrderException("Order must contain at least one item", 422);
        }

    }

    private function validateOrderItemsQuantity(): void
    {
        foreach($this->getItems() as $orderItem){
            if($orderItem->getQuantity() <= 0){
                throw new InvalidOrderItemQuantityException("Item quantity cannot be less or equal zero", 422);
            }
        }
    }

    private function validateUniqueProductEntries(): void
    {
        $productItemsIds = [];
        
        foreach($this->getItems() as $orderItem){

            $productIdentifier = $orderItem->getProduct()->getId()->getIdentifier();

            if(in_array($productIdentifier, $productItemsIds)){
                throw new OrderWithDuplicateProductEntyException(
                    "An order should not contain multiple order items with the same product ID.
                    Please specify the quantity for each item",
                    422
                );
            }

            $productItemsIds[] = $productIdentifier;
        }
    }

}
