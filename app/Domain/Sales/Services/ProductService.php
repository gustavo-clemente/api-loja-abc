<?php

declare(strict_types=1);

namespace App\Domain\Sales\Service;

use App\Domain\Sales\Repository\ProductRepository;
use Exception;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
        
    }

    public function getAllProducts(): array
    {
        throw new Exception('Not Implemented');
    }
}
