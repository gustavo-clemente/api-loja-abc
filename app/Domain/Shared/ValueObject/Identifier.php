<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

abstract class Identifier
{
    public function __construct(
        private string $identifier
    ) {
        
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
