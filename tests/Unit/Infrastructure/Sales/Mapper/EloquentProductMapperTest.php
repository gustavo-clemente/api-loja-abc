<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Sales\Mapper;

use App\Infrastructure\Sales\Mapper\EloquentProductMapper;
use App\Infrastructure\Sales\Model\ProductModel;
use Tests\TestCase;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\Entity\ProductCollection;
use App\Domain\Sales\ValueObject\ProductId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductMapperTest extends TestCase
{
    public function testMapToDomainMapsProductModelToProduct(): void
    {

        $productModel = $this->createProductModelMock(
            id: 1,
            name: 'product for test 1',
            price: 10.99,
            description: 'product description 1',
            createdAt: Carbon::now(),
            updatedAt: Carbon::now(),
        );

        /** @var EloquentProductMapper */
        $mapper = app(EloquentProductMapper::class);

        $product = $mapper->mapToDomain($productModel);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertInstanceOf(ProductId::class, $product->getId());
        $this->assertEquals($productModel->id, $product->getId()->getIdentifier());
        $this->assertEquals($productModel->name, $product->getName());
        $this->assertEquals($productModel->price, $product->getPrice());
        $this->assertEquals($productModel->description, $product->getDescription());
        $this->assertEquals($productModel->created_at, $product->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals($productModel->updated_at, $product->getUpdatedAt()->format('Y-m-d H:i:s'));
    }

    public function testMapToDomainCollectionMapsProductModelCollectionToProductCollection(): void
    {
        $collection = new Collection([
            $this->createProductModelMock(
                id: 1,
                name: 'product for test 2',
                price: 10.99,
                description: 'product description 2',
                createdAt: Carbon::now(),
                updatedAt: Carbon::now(),
            ),
            $this->createProductModelMock(
                id: 1,
                name: 'product for test 3',
                price: 10.99,
                description: 'product description 3',
                createdAt: Carbon::now(),
                updatedAt: Carbon::now(),
            ),
            $this->createProductModelMock(
                id: 1,
                name: 'product for test 4',
                price: 10.99,
                description: 'product description 4',
                createdAt: Carbon::now(),
                updatedAt: Carbon::now(),
            )

        ]);

        /** @var EloquentProductMapper */
        $mapper = app(EloquentProductMapper::class);

        $productCollection = $mapper->mapToDomainCollection($collection);

        $this->assertInstanceOf(ProductCollection::class, $productCollection);
        $this->assertCount(3, $productCollection->getItems());
    }

    private function createProductModelMock(
        int $id,
        string $name,
        float $price,
        string $description,
        Carbon $createdAt,
        Carbon $updatedAt

    ): ProductModel {
        /** @var ProductModel */
        $productModel = $this
            ->getMockBuilder(ProductModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productModel->method('__get')
            ->willReturnCallback(function ($property) use($id, $name, $price, $description, $createdAt, $updatedAt){

                return match ($property) {
                    'id' => $id,
                    'name' => $name,
                    'price' => $price,
                    'description' => $description,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt
                };
            });

        return $productModel;
    }
}
