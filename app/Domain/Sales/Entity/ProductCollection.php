<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Shared\Entity\Collection;
use App\Domain\Sales\Entity\Product;

/** @method Product[] getItems() */
class ProductCollection extends Collection
{
}
