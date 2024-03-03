<?php

declare(strict_types=1);

namespace App\Domain\Sales\Repository;

use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;

interface ProductRepository
{
    public function findAll(?int $paginate = null): ProductCollection;
}
