<?php

declare(strict_types= 1);

namespace App\Application\Sales\Output;

use App\Domain\Sales\ValueObject\OrderId;
use Symfony\Component\HttpFoundation\Response;

class CreateOrderOutput implements \JsonSerializable
{
    public function __construct(
        private OrderId $orderId
    ){
    }

    public function jsonSerialize(): array
    {
        return [
            "status" => Response::HTTP_CREATED,
            "id" => $this->orderId->getIdentifier(),
        ];
    }
}
