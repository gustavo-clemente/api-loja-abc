<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\ProductId;
use DateTime;

class Product implements \JsonSerializable
{
    public function __construct(
        private ?ProductId $id,
        private string $name,
        private float $price,
        private string $description,
        private ?DateTime $createdAt = new DateTime(),
        private ?DateTime $updateAt = null
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updateAt;
    }

    public function jsonSerialize(): array
    {
        return [
            "id"=> $this->getId()->getIdentifier(),
            "name"=> $this->getName(),
            "price"=> $this->getPrice(),
            "description"=> $this->getDescription(),
            "createdAt" => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            "updatedAt" => $this->getUpdatedAt()?->format('Y-m-d H:i:s')
        ];
    }
}
