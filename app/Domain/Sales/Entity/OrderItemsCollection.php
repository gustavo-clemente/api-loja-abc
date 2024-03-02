<?php

declare(strict_types= 1);

namespace App\Domain\Sales\Entity;
use App\Domain\Shared\Entity\Collection;
use OrderItems;

/** @method OrderItems[] getItems() */
class OrderItemsCollection extends Collection
{
}
