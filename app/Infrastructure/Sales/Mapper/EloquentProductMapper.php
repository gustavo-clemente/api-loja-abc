<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Mapper;

use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;
use App\Domain\Sales\ValueObject\ProductId;
use App\Infrastructure\Sales\Model\ProductModel;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductMapper
{
    public function mapToDomain(ProductModel $productModel): Product
    {
        return new Product(
            id: new ProductId((string)$productModel->id),
            name: $productModel->name,
            priceInCents: $productModel->price_in_cents,
            description: $productModel->description,
            createdAt: new \DateTime($productModel->created_at->toDateTimeString()),
            updatedAt: new \Datetime($productModel->updated_at->toDateTimeString()),
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
