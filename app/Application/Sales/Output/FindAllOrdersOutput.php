<?php

declare(strict_types= 1);

namespace App\Application\Sales\Output;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;

class FindAllOrdersOutput implements \JsonSerializable
{
    public function __construct(
        private OrderCollection $orders
    ) {

    }

    public function jsonSerialize(): array
    {
        return [
            "items" => $this->orders->jsonSerialize()
        ];
    }
}
