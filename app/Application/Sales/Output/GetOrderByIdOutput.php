<?php

declare(strict_types= 1);

namespace App\Application\Sales\Output;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;
use Symfony\Component\HttpFoundation\Response;

class GetOrderByIdOutput implements \JsonSerializable
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
