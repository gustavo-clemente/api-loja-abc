<?php

declare(strict_types=1);

namespace App\Application\Sales\Input;

use App\Domain\Sales\ValueObject\OrderId;

class CancelOrderInput
{
    public function __construct(
        private int|string $id
    ) {
    }

    public function getOrderId(): OrderId
    {
        return new OrderId($this->id);
    }
}
