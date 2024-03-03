<?php

declare(strict_types= 1);

namespace App\Application\Sales\Output;

use App\Domain\Sales\Entity\Order;
use Symfony\Component\HttpFoundation\Response;

class AddOrderItemsOutput implements \JsonSerializable
{
    public function __construct(
        private Order $order
    ) {

    }

    public function jsonSerialize(): array
    {
        return [
            "status" => Response::HTTP_OK,
            "data" => $this->order->jsonSerialize(),
        ];
    }
}
