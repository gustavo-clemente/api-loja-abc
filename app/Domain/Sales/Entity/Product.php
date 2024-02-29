<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\ProductId;

class Product
{
    public function __construct(
        private ?ProductId $id,
        private string $name,
        private float $price,
        private string $description
    ) {
        
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
