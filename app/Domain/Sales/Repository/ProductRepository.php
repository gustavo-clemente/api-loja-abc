<?php

declare(strict_types=1);

namespace App\Domain\Sales\Repository;

use App\Domain\Sales\Entity\Product;

interface ProductRepository
{
    /** @return Product[] */
    public function findAll(): array;
}
