<?php

declare(strict_types= 1);

namespace App\Domain\Sales\Entity;
use App\Domain\Shared\Entity\Collection;
use App\Domain\Sales\Entity\OrderItem;

/** @method OrderItem[] getItems() */
class OrderItemsCollection extends Collection implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return array_map(function(OrderItem $orderItem){
            return $orderItem->jsonSerialize();
        }, $this->getItems() ?? []);
    }
}
