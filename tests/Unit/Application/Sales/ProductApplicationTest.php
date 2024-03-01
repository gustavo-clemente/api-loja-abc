<?php

declare(strict_types= 1);

namespace Tests\Unit\Application\Sales;

use App\Application\Sales\Output\FindAllProductsOutput;
use App\Application\Sales\ProductApplication;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;
use App\Domain\Sales\Service\ProductService;
use App\Domain\Sales\ValueObject\ProductId;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductApplicationTest extends TestCase
{
    public function testGetAllReturnsCorrectOutput(): void
    {
        $productCollection = new ProductCollection([
            new Product(
                id: new ProductId('1'),
                name: 'Product 1',
                price: 500,
                description: 'Product 1 Description',
                createdAt: new \DateTime('2024-01-01 14:30:00'),
                updateAt: new \DateTime('2024-01-02 14:30:00'),
            ),
            new Product(
                id: new ProductId('2'),
                name: 'Product 2',
                price: 500,
                description: 'Product 2 Description',
                createdAt: new \DateTime('2024-02-01 16:30:00')
            ),
            new Product(
                id: new ProductId('3'),
                name: 'Product 3',
                price: 500,
                description: 'Product 3 Description',
                createdAt: new \DateTime('2024-03-01 18:30:00')
            ),
        ]);

        $this->mock(ProductService::class, function (MockInterface $mock) use($productCollection){
            $mock
              ->shouldReceive("getAllProducts")
              ->once()
              ->andReturn($productCollection);
        });

        $outPut = app(ProductApplication::class)->getAll();
        $products = $productCollection->getItems();

        $this->assertInstanceOf(FindAllProductsOutput::class, $outPut);
        $this->assertIsArray($outPut->getOutput());
        $this->assertArrayHasKey('items', $outPut->getOutput());
        $this->assertCount(3 , $outPut->getOutput()['items']);
        $this->assertEquals($products[0]->getName(), $outPut->getOutput()['items'][0]['name']);
        $this->assertEquals($products[0]->getPrice(), $outPut->getOutput()['items'][0]['price']);
        $this->assertEquals($products[0]->getDescription(), $outPut->getOutput()['items'][0]['description']);
        $this->assertEquals(
            $products[0]->getCreatedAt()->format('Y-m-d H:i:s'), 
            $outPut->getOutput()['items'][0]['createdAt']
        );
        $this->assertEquals(
            $products[0]->getUpdatedAt()->format('Y-m-d H:i:s') , $outPut->getOutput()['items'][0]['updatedAt']
        );
        $this->assertNull($outPut->getOutput()['items'][1]['updatedAt']);
    }
}
