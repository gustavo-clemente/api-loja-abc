<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\ProductId;
use Illuminate\Support\Facades\Date;

class Product
{
    public function __construct(
        private ?ProductId $id,
        private string $name,
        private float $price,
        private string $description,
        private Date $createdAt,
        private Date $updateAt
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

    public function getCreatedAt(): Date
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): Date
    {
        return $this->updateAt;
    }
}
