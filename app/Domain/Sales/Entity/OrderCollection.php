<?php

declare(strict_types= 1);

namespace App\Domain\Sales\Entity;
use App\Domain\Shared\Entity\Collection;
use App\Domain\Sales\Entity\Order;

/** @method Order[] getItems() */
class OrderCollection extends Collection implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return array_map(function(Order $order){
            return $order->jsonSerialize();
        }, $this->getItems() ?? []);
    }
}
