<?php

declare(strict_types=1);

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\ValueObject\ProductId;
use DateTime;

class Product implements \JsonSerializable
{
    public function __construct(
        private ?ProductId $id = null,
        private ?string $name = null,
        private ?int $priceInCents = null,
        private ?string $description = null,
        private ?DateTime $createdAt = null,
        private ?DateTime $updatedAt = null
    ) {
        
    }

    public function getId(): ?ProductId
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPriceInCents(): ?int
    {
        return $this->priceInCents;
    }

    public function getPriceInReal(): ?float
    {
        if(is_null($this->priceInCents)){
            return null;
        }

        return $this->priceInCents /100;

    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function jsonSerialize(): array
    {
        return [
            "id"=> $this->getId()->getIdentifier(),
            "name"=> $this->getName(),
            "price"=> $this->getPriceInReal(),
            "description"=> $this->getDescription(),
            "createdAt" => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
            "updatedAt" => $this->getUpdatedAt()?->format('Y-m-d H:i:s')
        ];
    }
}
