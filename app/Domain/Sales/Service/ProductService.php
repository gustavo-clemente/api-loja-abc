<?php

declare(strict_types=1);

namespace App\Domain\Sales\Service;

use App\Domain\Sales\Repository\ProductRepository;
use Exception;
use App\Domain\Sales\Entity\Product;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
        
    }

    /** @return Product[] */
    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }
}
