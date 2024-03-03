<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Repository\Eloquent;

use App\Domain\Sales\Repository\ProductRepository;
use App\Domain\Sales\Entity\ProductCollection;
use App\Infrastructure\Sales\Mapper\EloquentProductMapper;
use App\Infrastructure\Sales\Model\ProductModel;

class EloquentProductRepository implements ProductRepository
{
    public function __construct(
        private EloquentProductMapper $productMapper
    ) {
        
    }
    public function findAll(?int $paginate = null): ProductCollection
    {
        $products = ProductModel::all();

        return $this->productMapper->mapToDomainCollection($products);
    }
}
