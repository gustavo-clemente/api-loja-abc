<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Repository\Eloquent;

use App\Domain\Sales\Repository\ProductRepository;
use App\Domain\Sales\Entity\ProductCollection;
use App\Domain\Sales\Mapper\ProductMapper;
use App\Infrastructure\Sales\Model\ProductModel;

class EloquentProductRepository implements ProductRepository
{
    public function __construct(
        private ProductMapper $productMapper
    ) {
        
    }
    public function findAll(?int $paginate = null): ProductCollection
    {
        $products = ProductModel::all()->toArray();

        return $this->productMapper->mapToDomainCollection($products);
    }
}
