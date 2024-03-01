<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Mapper;

use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;
use App\Domain\Sales\ValueObject\ProductId;
use App\Infrastructure\Sales\Model\ProductModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;

class EloquentProductMapper
{
    public function mapToDomain(ProductModel $productModel): Product
    {
        return new Product(
            id: new ProductId((string)$productModel->id),
            name: $productModel->name,
            price: (float)$productModel->price,
            description: $productModel->description,
            createdAt: $productModel->created_at,
            updateAt: $productModel->updated_at
        );
    }

    public function mapToDomainCollection(Collection $collection): ProductCollection
    {
        $products = [];

        foreach ($collection as $product) {
            $products[] = $this->mapToDomain($product);
        }

        return new ProductCollection($products);
    }
}
