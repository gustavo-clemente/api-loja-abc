<?php

declare(strict_types= 1);

namespace App\Application\Sales\Output;

use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;

class FindAllProductsOutput
{
    public function __construct(
        private ProductCollection $products
    ) {

    }

    public function getOutput(): array
    {
        return [
            "items" => array_map(function(Product $product) {
                return $product->jsonSerialize();
            }, $this->products->getItems())
        ];
    }
}
