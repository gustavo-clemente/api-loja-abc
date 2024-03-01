<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Sales\Repository\Eloquent;

use App\Domain\Sales\Entity\ProductCollection;
use App\Infrastructure\Sales\Model\ProductModel;
use App\Infrastructure\Sales\Repository\Eloquent\EloquentProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpParser\Node\Expr\FuncCall;
use Tests\TestCase;

class EloquentProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testFindAllReturnProductCollection(): void
    {
        ProductModel::factory(10)->create();

        $productCollection = app(EloquentProductRepository::class)->findAll();

        $this->assertInstanceOf(ProductCollection::class, $productCollection);
        $this->assertCount(10, $productCollection->getItems());
    }
}

