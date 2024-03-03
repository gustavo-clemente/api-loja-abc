<?php

declare(strict_types=1);

namespace App\Domain\Shared\Entity;

abstract class Collection
{
    public function __construct(
        private array $items
    ) {
        
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
