<?php

declare(strict_types= 1);

namespace App\Application\Sales;

use App\Application\Sales\Output\FindAllProductsOutput;
use App\Domain\Sales\Service\ProductService;

class ProductApplication
{
    public function __construct(
        private ProductService $productService
    ){

    }

    public function getAll(): FindAllProductsOutput
    {
        $productCollection = $this->productService->getAllProducts();

        return new FindAllProductsOutput($productCollection);
    }
}
