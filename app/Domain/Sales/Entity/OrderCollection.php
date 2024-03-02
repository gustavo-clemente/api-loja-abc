<?php

declare(strict_types= 1);

namespace App\Domain\Sales\Entity;
use App\Domain\Shared\Entity\Collection;
use App\Domain\Sales\Entity\OrderItem;

/** @method OrderItem[] getItems() */
class OrderCollection extends Collection
{
}
