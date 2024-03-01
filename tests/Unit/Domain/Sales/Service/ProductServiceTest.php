<?php

declare(strict_types= 1);

namespace Tests\Unit\Domain\Sales\Service;

use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;
use App\Domain\Sales\Repository\ProductRepository;
use App\Domain\Sales\Service\ProductService;
use App\Domain\Sales\ValueObject\ProductId;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    public function testGetAllProductsReturnsProductArray(): void
    {
        $this->mock(ProductRepository::class, function (MockInterface $mock): void {
            $mock
              ->shouldReceive("findAll")
              ->once()
              ->andReturn(new ProductCollection([
                new Product(
                    id: new ProductId("1"),
                    name: "Product 1",
                    price: 2000,
                    description: ""
                ),
                new Product(
                    id: new ProductId("2"),
                    name: "Product 2",
                    price: 2000,
                    description: ""
                ),
                new Product(
                    id: new ProductId("3"),
                    name: "Product 3",
                    price: 2000,
                    description: ""
                )
              ]));
        });

        $products = app(ProductService::class)->getAllProducts();

        $this->assertIsArray($products);
        $this->assertInstanceOf(ProductCollection::class, $products);
        $this->assertContainsOnlyInstancesOf(Product::class, $products);
        $this->assertCount(3, $products);
    }
}
