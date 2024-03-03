<?php

declare(strict_types= 1);

namespace App\Domain\Sales\Exception;

class OrderNotFoundException extends SalesException
{
    public function __construct($message = "Order ID not found", $code = 404)
    {
        parent::__construct($message, $code);
    }
}
