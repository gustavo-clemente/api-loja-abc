<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Mapper;

use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;
use App\Domain\Sales\Mapper\ProductMapper;
use App\Domain\Sales\ValueObject\ProductId;
use Illuminate\Support\Facades\Date;

class EloquentProductMapper implements ProductMapper
{
    public function mapToDomain(array $item): Product
    {
        return new Product(
            id: new ProductId($item['id']),
            name: $item['name'],
            price: $item['price'],
            description: $item['description'],
            createdAt: new Date($item['created_at']),
            updateAt: new Date($item['updated_at'])
        );
    }

    public function mapToDomainCollection(array $items): ProductCollection
    {
        $products = [];

        foreach($items as $item){
            $products[] = $this->mapToDomain($item);
        }

        return new ProductCollection($products);
    }
}
